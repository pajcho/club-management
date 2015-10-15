<?php namespace App\Modules\Members\Models;

use App\Models\BaseModel;

class DateHistory extends BaseModel {
    
    protected $table = 'date_history';
    protected $fillable = array('date', 'value', 'type');
    protected $dates = array('date');

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}