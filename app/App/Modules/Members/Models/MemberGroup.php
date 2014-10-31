<?php namespace App\Modules\Members\Models;

use App\Internal\HistorableTrait;
use App\Models\BaseModel;
use Carbon\Carbon;

class MemberGroup extends BaseModel {

    /** History */
    use HistorableTrait;
    public function historyTable()
    {
        return 'member group';
    }

    public function historyTitle()
    {
        return '<strong>' . link_to_route('group.show', $this->name, $this->id) . '</strong>';
    }

    public $timestamps = true;
    
    protected $table = 'member_groups';
    protected $softDelete = false;
    
    protected $fillable = array('name', 'location', 'description', 'training', 'data');

    protected $appends = array('total_monthly_time', 'trainer_ids');

    public function trainers()
    {
        return $this->belongsToMany('App\Modules\Users\Models\User', 'users_groups', 'group_id', 'user_id')->withTimestamps();
    }

    /**
     * Get groups trained by certain trainer
     *
     * @param $query
     * @param $trainer = Can be either trainer object or trainer id
     * @return mixed
     */
    public function scopeTrainedBy($query, $trainer)
    {
        $trainerId = is_numeric($trainer) ? $trainer : $trainer->id;

        return $query->whereHas('trainers', function($q) use ($trainerId){
            $q->where('user_id', $trainerId);
        });
    }

    public function members()
    {
        return $this->hasMany('App\Modules\Members\Models\Member', 'group_id');
    }

    public function data($year = null, $month = null, $member_id = null)
    {
        $relation = $this->hasMany('App\Modules\Members\Models\MemberGroupData', 'group_id');

        if(is_numeric($year)) $relation = $relation->where('member_group_data.year', $year);
        if(is_numeric($month)) $relation = $relation->where('member_group_data.month', $month);
        if(is_numeric($member_id)) $relation = $relation->where('member_group_data.member_id', $member_id);

        // If we pass all filters than return only one result
        if(is_numeric($year) && is_numeric($month) && is_numeric($member_id)) $relation = $relation->first();
        // Otherwise return all we found
        else if(is_numeric($year) || is_numeric($month) || is_numeric($member_id)) $relation = $relation->get();

        return $relation;
    }

    public function payedString($year = null, $month = null)
    {
        $this->members = app('App\Modules\Members\Repositories\MemberRepositoryInterface');

        // Get all group members
        $members = $this->members->filter(array(
            'group_id'          => $this->id,
            'subscribed'        => array('<=', Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateTimeString()),
            'orderBy'           => array('dos' => 'asc'),
        ), false);

        // Get only members active in this month
        $activeMembers = $members->filter(function($member) use ($year, $month){
            return $member->inGroupOnDate($this->id, $year, $month) && $member->activeOnDate($year, $month);
        })->values();

        $freeOfChargeMembers = $activeMembers->filter(function($member) use ($year, $month){
            return $member->freeOfChargeOnDate($year, $month);
        })->values();

        $payedMembers = $this->data($year, $month)->filter(function($memberData) use ($activeMembers){
            $hasMember = $activeMembers->filter(function($activeMember) use ($memberData){
                return $activeMember->id == $memberData->member_id;
            })->values();

            return $memberData->payed && count($hasMember);
        });

        $totalMembersCount = count($activeMembers) - count($freeOfChargeMembers);
        $payedMembersCount = count($payedMembers);

        $buttonClass = $payedMembersCount && ($totalMembersCount / $payedMembersCount == 1) ? 'success' : 'default';

        // Generate string to return
        $payingMembersString = '<span class="bold btn btn-xs btn-' . $buttonClass . '">'.$payedMembersCount.' / '.$totalMembersCount.'</span>';

        return $payingMembersString;

    }

    /**
     * Get ids of all trainers of this group
     * Used mainly for select boxes
     *
     * @return string
     */
    public function getTrainerIdsAttribute()
    {
        return $this->trainers()->get()->lists('id');
    }

    /**
     * Make sure we always have training days as object
     * even if user has not yet initiated it
     *
     * @return mixed|\stdClass
     */
    public function getTrainingAttribute()
    {
        $training = json_decode($this->attributes['training']);

        if(!is_object($training))
        {
            $training = new \stdClass();
            $training->monday = NULL;
            $training->tuesday = NULL;
            $training->wednesday = NULL;
            $training->thursday = NULL;
            $training->friday = NULL;
            $training->saturday = NULL;
            $training->sunday = NULL;
        }
        else
        {
            foreach($training as $key => $value)
            {
                if(!($value->start && $value->end)) $training->{$key} = NULL;
            }
        }

        return $training;
    }

    /**
     * Get total training hours in one week
     *
     * @return int
     */
    public function getTotalMonthlyTimeAttribute()
    {
        $total = 0;
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        while($startOfMonth->lte($endOfMonth))
        {
            foreach($this->training as $key => $day)
            {
                $dayName = strtoupper($key);
                if($day && constant("Carbon\Carbon::$dayName") == $startOfMonth->dayOfWeek)
                {
                    $start = Carbon::createFromFormat('H:i', $day->start);
                    $end = Carbon::createFromFormat('H:i', $day->end);
                    $total += $start->diffInMinutes($end);
                }
            }

            $startOfMonth->addDay();
        }

        return number_format($total/60, 2);
    }

    /**
     * Get all training days for current month
     *
     * @param $year
     * @param $month
     * @return int
     */
    public function trainingDays($year, $month)
    {
        $days = array();
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        while($startOfMonth->lte($endOfMonth))
        {
            foreach($this->training as $key => $day)
            {
                $dayName = strtoupper($key);
                if($day && constant("Carbon\Carbon::$dayName") == $startOfMonth->dayOfWeek)
                {
                    array_push($days, $startOfMonth->copy());
                }
            }

            $startOfMonth->addDay();
        }

        return $days;
    }
}