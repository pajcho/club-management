<?php namespace App\Modules\Results\Models;

use App\Internal\HistorableTrait;
use App\Models\BaseModel;

class ResultCategory extends BaseModel {

    /** History */
    use HistorableTrait;
    public function historyTable()
    {
        return 'result category';
    }

    public function historyTitle()
    {
        return '<strong>' . link_to_route('result.category.show', $this->name, $this->id) . '</strong>';
    }

    public $timestamps = true;
    
    protected $table = 'result_categories';
    protected $softDelete = false;
    
    protected $fillable = array('name', 'parent_id');

    public function parent()
    {
        return $this->hasOne('App\Modules\Results\Models\ResultCategory', 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany('App\Modules\Results\Models\ResultCategory', 'parent_id', 'id');
    }
}