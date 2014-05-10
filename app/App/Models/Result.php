<?php namespace App\Models;

use App\Internal\HistorableTrait;

class Result extends BaseModel {

    /** History */
    use HistorableTrait;
    public function historyTable()
    {
        return 'result';
    }

    public function historyTitle()
    {
        return 'for <strong>' . link_to_route('result.show', $this->member->full_name, $this->id) . '</strong>';
    }

    public $timestamps = true;
    
    protected $table = 'results';
    protected $softDelete = false;
    
    protected $fillable = array('member_id', 'category_id', 'year', 'place', 'type', 'notes');

    public function member()
    {
        return $this->belongsTo('App\Models\Member');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\ResultCategory', 'category_id');
    }
}