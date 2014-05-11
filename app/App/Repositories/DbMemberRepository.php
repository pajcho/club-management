<?php namespace App\Repositories;

use App\Models\DateHistory;
use App\Models\Member;
use DateTime;
use Illuminate\Support\Facades\Auth;

class DbMemberRepository extends DbBaseRepository implements MemberRepositoryInterface {

    protected $model;
    protected $columnNames;
    protected $orderBy = array('dob' => 'desc');
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
        // Delete member results
        if($item->results->count()) $item->results()->delete();
    }

    public function filter(array $params = array(), $paginate = true)
    {
        // Default filter by every database column
        foreach($this->columnNames as $column)
        {
            if(isset($params[$column]) && ($params[$column] === '0' || !empty($params[$column])))
            {
                $this->model = $this->model->where($column, '=', $params[$column]);
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
                    });
                    break;
                case 2:
                    $this->model = $this->model->where(function($query) use ($names){
                        $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                        $query->where('last_name', 'LIKE', '%' . $names[1] . '%');
                    })->orWhere(function($query) use ($names){
                        $query->where('last_name', 'LIKE', '%' . $names[0] . '%');
                        $query->where('first_name', 'LIKE', '%' . $names[1] . '%');
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

        // Order by
        foreach($this->orderBy as $orderBy => $orderDirection)
            $this->model = $this->model->orderBy($orderBy, $orderDirection);

        $this->preReturnFilters();

        return $paginate ? $this->model->paginate($this->perPage) : $this->model->get();
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

        $this->updateHistory($member, $input, 'active', true);
        $this->updateHistory($member, $input, 'freeOfCharge', true);

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

        return $member->update($input);
    }

    /**
     * Helper function to update user date history status
     *
     * @param $member
     * @param $input
     * @param $type
     * @param bool $force
     */
    private function updateHistory($member, $input, $type, $force = false)
    {
        // Only update if it is different value than before
        if($force || $member->$type != array_get($input, $type, 1))
        {
            $history = new DateHistory(array(
                'date' => new DateTime,
                'value' => array_get($input, $type, 1),
                'type' => $type,
            ));

            $member->{$type . 'History'}()->save($history);
        }
    }

    /**
     * Get all users as array to use for select box
     */
    public function getForSelect()
    {
        return $this->all()->lists('full_name', 'id');
    }
}