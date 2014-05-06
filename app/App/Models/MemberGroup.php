<?php namespace App\Models;

use App\Internal\HistorableTrait;
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
        return $this->name;
    }

    public $timestamps = true;
    
    protected $table = 'member_groups';
    protected $softDelete = false;
    
    protected $fillable = array('name', 'location', 'description', 'training', 'details');

    protected $appends = array('total_monthly_time', 'trainer_ids');

    public function trainers()
    {
        return $this->belongsToMany('User', 'users_groups', 'group_id', 'user_id')->withTimestamps();
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
        return $this->hasMany('App\Models\Member', 'group_id');
    }

    public function details($year = null, $month = null)
    {
        $relation = $this->hasMany('App\Models\MemberGroupDetails', 'group_id');

        if(is_numeric($year)) $relation = $relation->where('member_group_details.year', $year);
        if(is_numeric($month)) $relation = $relation->where('member_group_details.month', $month);

        if(is_numeric($year) || is_numeric($month)) $relation = $relation->get();
        if(is_numeric($year) && is_numeric($month)) $relation = $relation->first();

        return $relation;
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
        $startOfMonth = Carbon::createFromDate($year, $month)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month)->endOfMonth();

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