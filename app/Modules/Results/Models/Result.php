<?php namespace App\Modules\Results\Models;

use App\Internal\HistorableTrait;
use App\Models\BaseModel;

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
    
    protected $fillable = array('member_id', 'category_id', 'subcategory', 'year', 'place', 'type', 'notes');

    public function member()
    {
        return $this->belongsTo('App\Modules\Members\Models\Member');
    }

    public function category()
    {
        return $this->belongsTo('App\Modules\Results\Models\ResultCategory', 'category_id');
    }
}