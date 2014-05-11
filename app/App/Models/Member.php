<?php namespace App\Models;

use App\Internal\HistorableTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Member extends BaseModel {

    /** History */
    use HistorableTrait;
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
    
    protected $fillable = array('group_id', 'uid', 'first_name', 'last_name', 'phone', 'notes', 'dob', 'dos', 'doc', 'active', 'freeOfCharge');
    protected $dates = array('dob', 'dos', 'doc');
    protected $appends = array('full_name');

    public function group()
    {
        return $this->belongsTo('App\Models\MemberGroup');
    }

    public function activeHistory()
    {
        return $this->hasMany('App\Models\DateHistory')->where('type', 'active');
    }

    public function freeOfChargeHistory()
    {
        return $this->hasMany('App\Models\DateHistory')->where('type', 'freeOfCharge');
    }

    public function results()
    {
        return $this->hasMany('App\Models\Result');
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
        $item = $this->activeHistory()->orderBy('date', 'desc')
            ->where(DB::raw('YEAR(date)'), $year)
            ->where(DB::raw('MONTH(date)'), '<=', $month)->get()->first();

        if($item)
        {
            return $item->value ? true : false;
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
        $item = $this->freeOfChargeHistory()->orderBy('date', 'desc')
            ->where(DB::raw('YEAR(date)'), $year)
            ->where(DB::raw('MONTH(date)'), '<=', $month)->get()->first();

        if($item)
        {
            return $item->value ? true : false;
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
}