<?php namespace App\Modules\Members\Models;

use App\Models\BaseModel;

class DateHistory extends BaseModel {
    
    public $timestamps = true;

    protected $table = 'date_history';
    protected $softDelete = false;
    
    protected $fillable = array('date', 'value', 'type');
    protected $dates = array('date');

    public function member()
    {
        return $this->belongsTo('App\Modules\Members\Models\Member');
    }
}