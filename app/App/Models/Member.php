<?php namespace App\Models;

use Carbon\Carbon;

class Member extends BaseModel {
    
    public $timestamps = true;
    
    protected $table = 'members';
    protected $softDelete = false;
    
    protected $fillable = array('group_id', 'uid', 'first_name', 'last_name', 'phone', 'notes', 'dob', 'dos', 'doc', 'active');
    protected $dates = array('dob', 'dos', 'doc');
    protected $appends = array('full_name');

    public function group()
    {
        return $this->belongsTo('App\Models\MemberGroup');
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