<?php namespace App\Modules\Members\Models;

use App\Internal\HistorableTrait;
use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Member extends BaseModel {

    /** History */
    use HistorableTrait;
    protected $temp;

    public function historyTable()
    {
        return 'member';
    }

    public function historyTitle()
    {
        return '<strong>' . link_to_route('member.show', $this->full_name, $this->id) . '</strong>';
    }

    public $timestamps = true;
    
    protected $table = 'members';
    protected $softDelete = false;
    
    protected $fillable = ['group_id', 'uid', 'first_name', 'last_name', 'email', 'phone', 'notes', 'dob', 'dos', 'doc', 'active', 'freeOfCharge'];
    protected $dates = ['dob', 'dos', 'doc'];
    protected $appends = ['full_name'];

    public function group()
    {
        return $this->belongsTo('App\Modules\Members\Models\MemberGroup');
    }

    public function data()
    {
        return $this->hasMany('App\Modules\Members\Models\MemberGroupData');
    }

    public function dateHistory($type = false)
    {
        $relationship = $this->hasMany('App\Modules\Members\Models\DateHistory');

        // Filter by type if required to do so
        return is_string($type) ? $relationship->where('type', $type) : $relationship;
    }

    public function results()
    {
        return $this->hasMany('App\Modules\Results\Models\Result');
    }

    public function trainers()
    {
        return $this->group->trainers();
    }

    /**
     * Get members trained by certain trainer
     *
     * @param $query
     * @param $trainer = Can be either trainer object or trainer id
     * @return mixed
     */
    public function scopeTrainedBy($query, $trainer)
    {
        $trainerId = is_numeric($trainer) ? $trainer : $trainer->id;

        return $query->whereExists(function($query) use ($trainerId){
            $query->select(DB::raw(1))
                ->from('users_groups')
                ->where('users_groups.user_id', '=', $trainerId)
                ->whereRaw('users_groups.group_id = members.group_id');
        });
    }

    /**
     * Check if user was active on given month in year
     *
     * @param $year
     * @param $month
     * @param $default = This value will be returned if no results are found for desired date
     * @return bool
     */
    public function activeOnDate($year, $month, $default = true)
    {
        $date = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $item = $this->getDateHistory('active', $date);
//        $item = $this->dateHistory('active')->orderBy('date', 'desc')
//            ->where('date', '<=', $date)->get()->first();

        if($item)
        {
            return $item->value ? true : false;
        }

        return $default;
    }

    /**
     * Check if user was active in given range
     *
     * @param $startYear
     * @param $startMonth
     * @param $endYear
     * @param $endMonth
     * @param $default = This value will be returned if no results are found for desired date
     * @return bool
     */
    public function activeInRange($startYear, $startMonth, $endYear, $endMonth, $default = true)
    {
        $start = Carbon::createFromDate($startYear, $startMonth, 1)->startOfMonth()->startOfDay()->toDateTimeString();
        $end = Carbon::createFromDate($endYear, $endMonth, 1)->endOfMonth()->endOfDay()->toDateTimeString();

        // @TODO Fix this to use laravel's query builder instead of raw query
        $query = "select dh.id, dh.member_id, dh.date, dh.value, dh.type from `date_history` dh
            join (
                select id, member_id, MAX(date) max_date, value, type from `date_history`
                where `member_id` = {$this->id} and `type` = 'active'
                and date BETWEEN '$start' and '$end'
                group by YEAR(date), MONTH(date)
            ) sub_dh ON (dh.date = sub_dh.max_date)
            where dh.`member_id` = {$this->id} and dh.`type` = 'active'
            and dh.date BETWEEN '$start' and '$end'
            order by dh.`date` desc";

        $result = DB::select($query);

        if($result)
        {
            $result = array_where($result, function($key, $value)
            {
                return $value->value == 1;
            });

            return $result ? true : false;
        }

        return $default;
    }

    /**
     * Check if user was free of charge on given month in year
     *
     * @param $year
     * @param $month
     * @param $default = This value will be returned if no results are found for desired date
     * @return bool
     */
    public function freeOfChargeOnDate($year, $month, $default = false)
    {
        $date = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $item = $this->getDateHistory('freeOfCharge', $date);
//        $item = $this->dateHistory('freeOfCharge')->orderBy('date', 'desc')
//            ->where('date', '<=', $date)->get()->first();

        if($item)
        {
            return $item->value ? true : false;
        }

        return $default;
    }

    /**
     * Check if user was in a given group on given month in year
     *
     * @param $groupId
     * @param $year
     * @param $month
     * @param bool $default = This value will be returned if no results are found for desired date
     * @return bool
     */
    public function inGroupOnDate($groupId, $year, $month, $default = false)
    {
        $date = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $item = $this->getDateHistory('group_id', $date);
//        $item = $this->dateHistory('group_id')->orderBy('date', 'desc')
//            ->where('date', '<=', $date)->get()->first();

        if($item)
        {
            return $item->value == $groupId ? true : false;
        }

        return $default;
    }

    /**
     * Get member group on given month in year
     *
     * @param $year
     * @param $month
     * @param bool $default = This value will be returned if no results are found for desired date
     * @return bool
     */
    public function getGroupOnDate($year, $month, $default = false)
    {
        $date = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $item = $this->getDateHistory('group_id', $date);
//        $item = $this->dateHistory('group_id')->orderBy('date', 'desc')
//            ->where('date', '<=', $date)->get()->first();

        return $item ? $item->value : $default;
    }

    /**
     * Check if user was in given group in given range
     *
     * @param $groupId
     * @param $startYear
     * @param $startMonth
     * @param $endYear
     * @param $endMonth
     * @param bool $default = This value will be returned if no results are found for desired date
     * @return bool
     */
    public function inGroupInRange($groupId, $startYear, $startMonth, $endYear, $endMonth, $default = true)
    {
        $start = Carbon::createFromDate($startYear, $startMonth, 1)->startOfMonth()->startOfDay()->toDateTimeString();
        $end = Carbon::createFromDate($endYear, $endMonth, 1)->endOfMonth()->endOfDay()->toDateTimeString();

        // @TODO Fix this to use laravel's query builder instead of raw query
        $query = "select dh.id, dh.member_id, dh.date, dh.value, dh.type from `date_history` dh
            join (
                select id, member_id, MAX(date) max_date, value, type from `date_history`
                where `member_id` = {$this->id} and `type` = 'group_id'
                and date BETWEEN '$start' and '$end'
                group by YEAR(date), MONTH(date)
            ) sub_dh ON (dh.date = sub_dh.max_date)
            where dh.`member_id` = {$this->id} and dh.`type` = 'group_id'
            and dh.date BETWEEN '$start' and '$end'
            order by dh.`date` desc";

        $result = DB::select($query);

        if($result)
        {
            $result = array_where($result, function($key, $value) use ($groupId)
            {
                return $value->value == $groupId;
            });

            return $result ? true : false;
        }

        return $default;
    }

    /**
     * Get member full name
     * 
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    /**
     * Get medical examination button class
     *
     * @return string
     */
    public function getMedicalExaminationClass(){
        return is_object($this->doc) ? ($this->doc->gte(Carbon::now()->startOfDay()) ? 'btn-success' : 'btn-danger') : 'btn-warning';
    }

    /**
     * Get medical examination button title
     *
     * @return string
     */
    public function getMedicalExaminationTitle(){
        return is_object($this->doc) ? ($this->doc->gte(Carbon::now()->startOfDay()) ? 'Medical examination is valid.' : 'Medical examination expired.') : 'Medical examination not supplied.';
    }


    /**
     * Return date history from private property in order to lower down number of database queries
     *
     * @param $date
     *
     * @return mixed
     */
    private function getDateHistory($type, $date)
    {
        if(!isset($this->temp['getDateHistory'.$date]))
        {
            $this->temp['getDateHistory'.$date] = $this->dateHistory()->orderBy('date', 'desc')
                ->where('date', '<=', $date)->get();
        }

        $items = $this->temp['getDateHistory'.$date]->filter(function($item) use ($type){
            return $item->type == $type;
        });

        return $items ? $items->first() : null;
    }
}