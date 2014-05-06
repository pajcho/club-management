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
        return $this->title;
    }

    public $timestamps = false;

    protected $table = 'settings';
    protected $softDelete = false;
    
    protected $fillable = array('title', 'key', 'value', 'description');
}