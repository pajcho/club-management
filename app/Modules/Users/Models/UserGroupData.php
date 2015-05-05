<?php namespace App\Modules\Users\Models;

use App\Internal\HistorableTrait;
use App\Models\BaseModel;
use Carbon\Carbon;

class UserGroupData extends BaseModel {

    /** History */
    use HistorableTrait;
    public function historyTable()
    {
        return 'user group data';
    }

    public function historyTitle()
    {
        return sprintf(
            'for %s in %s for %s',
            '<strong>' . link_to_route('user.show', $this->user->full_name, $this->user->id) . '</strong>',
            '<strong>' . link_to_route('group.show', $this->group->name, $this->group->id) . '</strong>',
            '<strong>' . link_to_route('user.attendance.index', Carbon::create($this->year, $this->month, 1)->format('F, Y'), $this->user->id) . '</strong>'
        );
    }

    public $timestamps = true;
    
    protected $table = 'users_groups_data';
    protected $softDelete = false;
    
    protected $fillable = array('group_id', 'user_id', 'year', 'month', 'attendance');

    public function group()
    {
        return $this->belongsTo('App\Modules\Members\Models\MemberGroup');
    }

    public function user()
    {
        return $this->belongsTo('App\Modules\Users\Models\User');
    }

    /**
     * Get data owned by certain user
     *
     * @param $query
     * @param $user = Can be either user object or user id
     * @return mixed
     */
    public function scopeOwnedBy($query, $user)
    {
        $userId = is_numeric($user) ? $user : $user->id;

        return $query->where('user_id', $userId);
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