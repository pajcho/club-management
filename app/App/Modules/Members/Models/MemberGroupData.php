<?php namespace App\Modules\Members\Models;

use App\Internal\HistorableTrait;
use App\Models\BaseModel;
use Carbon\Carbon;

class MemberGroupData extends BaseModel {

    /** History */
    use HistorableTrait;
    public function historyTable()
    {
        return 'member group data';
    }

    public function historyTitle()
    {
        return sprintf(
            'for %s in %s for %s',
            '<strong>' . link_to_route('member.show', $this->member->full_name, $this->member->id) . '</strong>',
            '<strong>' . link_to_route('group.show', $this->group->name, $this->group->id) . '</strong>',
            '<strong>' . link_to_route('group.data.show', Carbon::createFromDate($this->year, $this->month)->format('F') . ', ' . $this->year, array($this->group->id, $this->year, $this->month)) . '</strong>'
        );
    }

    public $timestamps = true;
    
    protected $table = 'member_group_data';
    protected $softDelete = false;
    
    protected $fillable = array('group_id', 'member_id', 'year', 'month', 'payed', 'attendance');

    public function group()
    {
        return $this->belongsTo('App\Modules\Members\Models\MemberGroup');
    }

    public function member()
    {
        return $this->belongsTo('App\Modules\Members\Models\Member');
    }

    public function attendance($search = null)
    {
        $attendance = json_decode($this->attributes['attendance']);

        // convert to array
        $attendance = json_decode(json_encode($attendance), true);

        // We return everything we find
        if(is_null($search)) return $attendance;

        // Otherwise we search for result
        return is_array($attendance) ? array_get($attendance, $search, 0) : null;
    }
}