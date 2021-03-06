<?php namespace App\Modules\Members\Models;

use App\Internal\HistorableTrait;
use App\Models\BaseModel;
use App\Modules\Members\Repositories\MemberRepositoryInterface;
use App\Modules\Users\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class MemberGroup extends BaseModel {

    /** History */
    use HistorableTrait;
    use SoftDeletes;
    protected $temp;

    public function historyTable()
    {
        return 'member group';
    }

    public function historyTitle()
    {
        return '<strong>' . link_to_route('group.show', $this->name, $this->id) . '</strong>';
    }

    protected $table = 'member_groups';
    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'location', 'description', 'training', 'data'];
    protected $appends = ['total_monthly_time', 'trainer_ids'];

    public function trainers()
    {
        return $this->belongsToMany(User::class, 'users_groups', 'group_id', 'user_id')->withTimestamps();
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
        return $this->hasMany(Member::class, 'group_id');
    }

    public function data($year = null, $month = null, $member_id = null)
    {
        if(!isset($this->temp['data'.$year.$month.$member_id]))
        {
            $relation = $this->hasMany(MemberGroupData::class, 'group_id');

            if(is_numeric($year)) $relation = $relation->where('member_group_data.year', $year);
            if(is_numeric($month)) $relation = $relation->where('member_group_data.month', $month);
            if(is_numeric($member_id)) $relation = $relation->where('member_group_data.member_id', $member_id);

            // If we pass all filters than return only one result
            if(is_numeric($year) && is_numeric($month) && is_numeric($member_id)) $relation = $relation->first();
            // Otherwise return all we found
            else if(is_numeric($year) || is_numeric($month) || is_numeric($member_id)) $relation = $relation->get();

            $this->temp['data'.$year.$month.$member_id] = $relation;
        }

        $relation = $this->temp['data'.$year.$month.$member_id];

        return $relation;
    }

    public function payedString($year = null, $month = null)
    {
        $tags = array('memberGroup', 'payedString', 'memberGroup:'.$this->attributes['id'], 'year:'.$year, 'month:'.$month);

        return Cache::tags($tags)->rememberForever(implode('|', $tags), function() use ($year, $month){
            $thisMembers = app(MemberRepositoryInterface::class);

            // Get only ids of members that are or were in this group at some time
            // This will lower number of required database queries to do all necessary calculations
            $memberIds = $thisMembers->thatAreInGroupOnDate($this->attributes['id'], $year, $month);

            // Get all group members
            $members = $thisMembers->filter([
                'ids'        => $memberIds,
                'subscribed' => ['<=', Carbon::create($year, $month, 1)->endOfMonth()->toDateTimeString()],
                'orderBy'    => ['dos' => 'asc'],
            ], false);

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
        });
    }

    /**
     * Get ids of all trainers of this group
     * Used mainly for select boxes
     *
     * @return string
     */
    public function getTrainerIdsAttribute()
    {
        return $this->trainers()->get()->lists('id')->all();
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

        return rtrim(number_format($total/60, 2), '.00');
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
        $days = [];
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

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
