<?php namespace App\Controllers;

use App\Internal\Validators\MemberValidator;
use App\Models\DateHistory;
use App\Repositories\MemberRepositoryInterface;
use App\Repositories\MemberGroupRepositoryInterface;
use App\Service\Theme;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class MemberController extends BaseController {

    private $members;
    private $groups;

    public function __construct(MemberRepositoryInterface $members, MemberGroupRepositoryInterface $groups)
    {
        parent::__construct();

        View::share('activeMenu', 'members');

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
        $input = Input::get();

        // Get all members
        $members = $this->members->filter($input);
        $groups = array('' => 'Group') + $this->groups->getForSelect();
        $locations = array('' => 'Location') + $this->groups->getLocationsForSelect();

        // Make user status search options
        $member_status = array(
            '' => 'All members',
            '1' => 'Active Members',
            '00' => 'Inactive Members'
        );

        // Generate filters title
        $filters_title = '';
        if(isset($member_status[Input::get('active', '')])) $filters_title = $member_status[Input::get('active', '')];
        if(isset($locations[Input::get('location') ?: false])) $filters_title = $locations[Input::get('location')] . ' / ' . $filters_title;
        if(isset($groups[Input::get('group_id') ?: false])) $filters_title = $groups[Input::get('group_id')] . ' / ' . $filters_title;
        if(Input::get('name') ?: false) $filters_title = Input::get('name') . ' / ' . $filters_title;

        return View::make(Theme::view('member.index'))->with(compact('members', 'groups', 'locations', 'member_status', 'filters_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $groups = $this->groups->getForSelect();
        return View::make(Theme::view('member.create'))->with(compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = new MemberValidator();

        if ($validator->validate(Input::all(), 'create'))
        {
            // validation passed
            $this->members->create($validator->data());

            // Create redirect depending on submit button
            $redirect = Redirect::route('member.index');

            if(Input::get('create_and_add', false))
                $redirect = Redirect::route('member.create');


            return $redirect->withSuccess('Member created!');
        }

        // validation failed
        return Redirect::route('member.create')->withInput()->withErrors($validator->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $member = $this->members->find($id);

        if(!$member) App::abort(404);

        $groups = $this->groups->getForSelect();

        return View::make(Theme::view('member.update'))->with(compact('member', 'groups'));
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
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $validator = new MemberValidator();

        if ($validator->validate(Input::all(), 'update', $id))
        {
            // validation passed
            $this->members->update($id, $validator->data());

            return Redirect::route('member.show', $id)->withSuccess('Details updated!');
        }

        // validation failed
        return Redirect::route('member.show', $id)->withInput()->withErrors($validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->members->delete($id);

        return Redirect::back()->withInput()->withSuccess('Member deleted!');
    }

}
