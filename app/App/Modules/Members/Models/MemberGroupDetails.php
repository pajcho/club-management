<?php namespace App\Modules\Members\Models;

use App\Internal\HistorableTrait;
use App\Models\BaseModel;
use Carbon\Carbon;

class MemberGroupDetails extends BaseModel {

    /** History */
    use HistorableTrait;
    public function historyTable()
    {
        return 'member group details';
    }

    public function historyTitle()
    {
        return sprintf(
            'for %s for %s',
            '<strong>' . link_to_route('group.show', $this->group->name, $this->group->id) . '</strong>',
            '<strong>' . link_to_route('group.details.show', Carbon::createFromDate($this->year, $this->month)->format('F') . ', ' . $this->year, array($this->group->id, $this->year, $this->month)) . '</strong>'
        );
    }

    public $timestamps = true;
    
    protected $table = 'member_group_details';
    protected $softDelete = false;
    
    protected $fillable = array('group_id', 'year', 'month', 'details');

    public function group()
    {
        return $this->belongsTo('App\Modules\Members\Models\MemberGroup');
    }

    public function details($search = null)
    {
        $details = json_decode($this->attributes['details']);

        // convert to array
        $details = json_decode(json_encode($details), true);

        // We return everything we find
        if(is_null($search)) return $details;

        // Otherwise we search for result
        return is_array($details) ? array_get($details, $search, 0) : null;
    }

    public function payed()
    {
        $details = json_decode($this->attributes['details']);

        // convert to array
        $details = array(json_decode(json_encode($details), true));

        if(is_array($details))
        {
            $details = head($details);

            if(is_array($details['payment']))
            {
                $total = count($details['payment']);
                $payed = count(array_where($details['payment'], function($key, $value){
                    return $value == 1;
                }));

                $prefix = $suffix = '';
                if($total == $payed)
                {
                    $prefix = '<span class="btn btn-xs btn-success">';
                    $suffix = '</span>';
                }

                return $prefix . $payed . '/' . $total . $suffix;
            }
        }

        return '';

    }
}