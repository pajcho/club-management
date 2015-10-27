<?php namespace App\Modules\Members\Repositories;

use App\Modules\Members\Models\DateHistory;
use App\Modules\Members\Models\Member;
use App\Repositories\DbBaseRepository;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DbMemberRepository extends DbBaseRepository implements MemberRepositoryInterface {

    protected $model;
    protected $columnNames;
    protected $orderBy = ['dob' => 'desc'];
    protected $perPage = 15;

    public function __construct(Member $model)
    {
        parent::__construct($model);
    }

    public function preReturnFilters()
    {
        parent::preReturnFilters();

        if($currentUser = Auth::user())
        {
            if($currentUser->isTrainer())
            {
                $this->model = $this->model->trainedBy($currentUser);
            }
        }
    }

    public function preDelete($item)
    {
        // Delete additional member data
        if($item->data->count()) $item->data()->delete();
        if($item->results->count()) $item->results()->delete();
        if($item->dateHistory->count()) $item->dateHistory()->delete();

        // Deleting users will happen so rare we can delete all member group data
        Cache::tags('memberGroup')->flush();
    }

    public function filter(array $params = [], $paginate = true)
    {
        $this->paginate = !!$paginate;

        // Default filter by every database column
        foreach($this->columnNames as $column)
        {
            if(isset($params[$column]) && ($params[$column] === '0' || !empty($params[$column])))
            {
                $operator = starts_with($params[$column], '%') || ends_with($params[$column], '%') ? 'LIKE' : '=';
                $this->model = $this->model->where($column, $operator, $params[$column]);
            }
        }

        // Set class properties
        foreach($params as $key => $param)
        {
            if(isset($this->{$key})) $this->{$key} = $param;
        }

        // Filter by name
        if(isset($params['name']) && !empty($params['name']))
        {
            $names = explode(' ', $params['name'], 2);

            switch(count($names))
            {
                case 1:
                    $this->model = $this->model->where(function($query) use ($names){
                        $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                        $query->orWhere('last_name', 'LIKE', '%' . $names[0] . '%');
                        $query->orWhere('email', 'LIKE', '%' . $names[0] . '%');
                    });
                    break;
                case 2:
                    $this->model = $this->model->where(function($query) use ($names){
                        $query->where(function($query) use ($names){
                            $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                            $query->where('last_name', 'LIKE', '%' . $names[1] . '%');
                        })->orWhere(function($query) use ($names){
                            $query->where('last_name', 'LIKE', '%' . $names[0] . '%');
                            $query->where('first_name', 'LIKE', '%' . $names[1] . '%');
                        });
                    });
                    break;
            }
        }

        // Filter by location
        if(isset($params['location']) && !empty($params['location']))
        {
            $this->model = $this->model->whereHas('group', function($query) use ($params){
                $query->where('location', '=', $params['location']);
            });
        }

        // Filter by date of subscription
        if(isset($params['subscribed']) && is_array($params['subscribed']))
        {
            $this->model = $this->model->where('dos', $params['subscribed'][0], $params['subscribed'][1]);
        }

        // Filter by member id
        if(isset($params['ids']) && is_array($params['ids']))
        {
            $this->model = $this->model->whereIn('id', $params['ids']);
        }

        // Order by
        foreach($this->orderBy as $orderBy => $orderDirection)
            $this->model = $this->model->orderBy($orderBy, $orderDirection);

        $this->preReturnFilters();

        return $this->paginate ? $this->model->paginate($this->perPage) : $this->model->get();
    }

    /**
     * We need to set active history before creating user
     *
     * @param $input
     * @return mixed
     */
    public function create($input)
    {
        $member = $this->model->create($input);

        $this->updateHistory($member, $input, 'active', true, $member->dos);
        $this->updateHistory($member, $input, 'freeOfCharge', true, $member->dos);
        $this->updateHistory($member, $input, 'group_id', true, $member->dos);

        return $member;
    }

    /**
     * We need to set active history before updating user
     *
     * @param $id
     * @param $input
     * @return mixed
     */
    public function update($id, $input)
    {
        $this->preReturnFilters();

        $member = $this->model->find($id);

        $this->updateHistory($member, $input, 'active');
        $this->updateHistory($member, $input, 'freeOfCharge');
        $this->updateHistory($member, $input, 'group_id');

        return $member->update($input);
    }

    /**
     * Helper function to update user date history status
     *
     * @param      $member
     * @param      $input
     * @param      $type
     * @param bool $force
     * @param null $date
     */
    private function updateHistory($member, $input, $type, $force = false, $date = null)
    {
        // Only update if it is different value than before
        if($force || $member->$type != array_get($input, $type, 1))
        {
            $history = new DateHistory([
                'date' => $date ?: new DateTime,
                'value' => array_get($input, $type, 1),
                'type' => $type,
            ]);

            $member->dateHistory()->save($history);

            // Clear related cache for old and new group
            $tags = ['memberGroup:'.$member->group_id, 'memberGroup:'.$input['group_id']];
            Cache::tags($tags)->flush();
        }
    }

    /**
     * Get all members that were in group at some time and return their ids
     *
     * @param $groupId
     *
     * @return array
     */
    public function thatAreInGroup($groupId)
    {
        return DateHistory::where('type', 'group_id')
            ->where('value', $groupId)
            ->get()->lists('member_id');
    }

    /**
     * Get all members that were in group on desired date and return their ids
     *
     * @param $groupId
     * @param $year
     * @param $month
     *
     * @return array
     */
    public function thatAreInGroupOnDate($groupId, $year, $month)
    {
        $date = Carbon::create($year, $month, 1)->endOfMonth();

        return DateHistory::where('type', 'group_id')
            ->where('value', $groupId)
            ->where('date', '<=', $date)
            ->get()->lists('member_id');
    }

    /**
     * Get all users as array to use for select box
     */
    public function getForSelect()
    {
        return $this->all()->lists('full_name', 'id');
    }
}
