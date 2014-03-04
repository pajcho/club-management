<?php namespace App\Models;

class Member extends BaseModel {
    
    public $timestamps = true;
    
    protected $table = 'members';
    protected $softDelete = false;
    
    protected $fillable = array('first_name', 'last_name', 'dob', 'dos');
    protected $dates = array('dob', 'dos');
    protected $appends = array('full_name');

    /**
     * Get member full name
     * 
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }
}