<?php namespace App\Models;

use App\Internal\HistorableTrait;

class Settings extends BaseModel {

    /** History */
    use HistorableTrait;
    public function historyTable()
    {
        return 'settings';
    }

    public function historyTitle()
    {
        return '<strong>' . link_to_route('settings.index', $this->title) . '</strong>';
    }

    public $timestamps = false;

    protected $table = 'settings';
    protected $softDelete = false;
    
    protected $fillable = array('title', 'key', 'value', 'description');
}