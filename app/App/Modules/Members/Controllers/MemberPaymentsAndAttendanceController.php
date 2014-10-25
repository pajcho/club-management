<?php namespace App\Modules\Members\Controllers;

use App\Controllers\AdminController;
use App\Internal\EditableMonthsTrait;
use App\Modules\Members\Models\MemberGroupData;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Members\Repositories\MemberRepositoryInterface;
use App\Service\Theme;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class MemberPaymentsAndAttendanceController extends AdminController {

    use EditableMonthsTrait;

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
     * @param int $id
     * @return Response
     */
    public function index($id)
    {
        $member = $this->members->findWith($id, array('data'));

        if(!$member) App::abort(404);

        $memberData = $member->data()->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('created_at', 'desc')->get();
        $data = [];

        $months = $this->getEditableMonths($member->dos->year, $member->dos->month);

        foreach($months as $month)
        {
            $monthData = $memberData->filter(function($memberDataItem) use ($month){
                return $memberDataItem->year == $month->year && $memberDataItem->month == $month->month;
            });

            if($monthData->count())
            {
                foreach($monthData->all() as $monthDataItem) array_push($data, $monthDataItem);
            }
            else
            {
                array_push($data, new MemberGroupData([
                    'group_id' => $member->getGroupOnDate($month->year, $month->month, $member->group_id),
                    'member_id' => $member->id,
                    'year' => $month->year,
                    'month' => $month->month,
                    'payed' => 0,
                    'attendance' => json_encode([]),
                ]));
            }
        }

        // Array of available years to be used as tabs on this page
        $years = array();
        foreach($data as $key => $value)
        {
            if(!in_array($value->year, $years)) array_push($years, $value->year);
        }

        return View::make(Theme::view('member.payments-and-attendance'))->with(compact('member', 'data', 'years'));
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
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
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
     * @param  int  $memberId
     * @return Response
     */
    public function update($memberId, $year, $month)
    {
        foreach(Input::get('data', array()) as $memberGroupId => $data)
        {
            $data = array(
                'member_id'     => $memberId,
                'year'          => $year,
                'month'         => $month,
                'payed'         => array_get($data, 'payed', 0),
                'attendance'    => json_encode($data['attendance']),
            );

            $this->groups->updateData($memberGroupId, $data);
        }

        return Redirect::route('member.payments.index', array($memberId))->withSuccess('Member data updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
