<?php namespace App\Modules\Results\Models;

use App\Internal\HistorableTrait;
use App\Models\BaseModel;
use App\Modules\Members\Models\Member;

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

    protected $table = 'results';
    protected $fillable = array('member_id', 'category_id', 'subcategory', 'year', 'place', 'type', 'notes');

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function category()
    {
        return $this->belongsTo(ResultCategory::class, 'category_id');
    }
}