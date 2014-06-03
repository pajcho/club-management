<?php namespace Api\Modules\Members\Controllers;

use Api\Controllers\ApiController;
use App\Modules\Members\Internal\Validators\MemberValidator;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Members\Repositories\MemberRepositoryInterface;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class MemberController extends ApiController {

    private $members;
    private $groups;

    public function __construct(MemberRepositoryInterface $members, MemberGroupRepositoryInterface $groups)
    {
        parent::__construct();

        $this->members = $members;
        $this->groups = $groups;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $paginate = Input::get('paginate', 'true') === 'true';
        $embeds = array_filter(explode(',', Input::get('embeds')));

        // Get all members
        $members = $this->members->filterWith($embeds, Input::get(), $paginate);

        // Filter members if requested
        $activeOnDate = Input::get('activeOnDate', array());
        if(isset($activeOnDate['year']) && isset($activeOnDate['month']))
        {
            // Get only members active in this month
            $members = $members->filter(function($member) use ($activeOnDate){
                return $member->activeOnDate($activeOnDate['year'], $activeOnDate['month']);
            })->values();
        }

        return $members;
    }

    /**
     * Return additional values used in filters
     *
     * @return Response
     */
    public function filters()
    {
        $groups = array('' => 'Group') + $this->groups->getForSelect();
        $locations = array('' => 'Location') + $this->groups->getLocationsForSelect();

        // Make user status search options
        $member_status = array(
            '' => 'All members',
            '1' => 'Active Members',
            '00' => 'Inactive Members'
        );
        $member_free = array(
            '' => 'All members',
            '00' => 'Paying Members',
            '1' => 'Free Members'
        );

        // Generate filters title
        $filters_title = '';
        if(isset($member_status[Input::get('active', '')])) $filters_title = $member_status[Input::get('active', '')];
        if(isset($member_free[Input::get('freeOfCharge', '')])) $filters_title = $member_free[Input::get('freeOfCharge', '')] . ' / ' . $filters_title;
        if(isset($locations[Input::get('location') ?: false])) $filters_title = $locations[Input::get('location')] . ' / ' . $filters_title;
        if(isset($groups[Input::get('group_id') ?: false])) $filters_title = $groups[Input::get('group_id')] . ' / ' . $filters_title;
        if(Input::get('name') ?: false) $filters_title = Input::get('name') . ' / ' . $filters_title;

        return compact('groups', 'locations', 'member_status', 'member_free', 'filters_title');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Dingo\Api\Exception\StoreResourceFailedException
     * @return Response
     */
    public function store()
    {
        $validator = new MemberValidator();

        if ($validator->validate(Input::all(), 'create'))
        {
            // validation passed
            return $this->members->create($validator->data());
        }

        // validation failed
        throw new StoreResourceFailedException('Could not create new member.', $validator->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $embeds = array_filter(explode(',', Input::get('embeds')));

        $member = $this->members->findWith($id, $embeds);

        if(!$member) App::abort(404);

        return $member;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @throws \Dingo\Api\Exception\UpdateResourceFailedException
     * @return Response
     */
    public function update($id)
    {
        $validator = new MemberValidator();

        if ($validator->validate(Input::all(), 'update', $id))
        {
            // validation passed
            return $this->members->update($id, $validator->data());
        }

        // validation failed
        throw new UpdateResourceFailedException('Could not update member.', $validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if($this->members->find($id)) $this->members->delete($id);

        return array(
            'deleted'   => true,
            'id'        => $id,
        );
    }

}
