<?php namespace App\Models;

use Carbon\Carbon;

class MemberGroup extends BaseModel {
    
    public $timestamps = true;
    
    protected $table = 'member_groups';
    protected $softDelete = false;
    
    protected $fillable = array('name', 'location', 'description', 'training');

    protected $appends = array('total_time');

    public function members()
    {
        return $this->hasMany('App\Models\Member', 'group_id');
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
                    $total += $start->diffInHours($end);
                }
            }

            $startOfMonth->addDay();
        }

        return $total;
    }

}