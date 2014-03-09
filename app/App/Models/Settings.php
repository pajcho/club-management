<?php namespace App\Models;

class Settings extends BaseModel {
    
    public $timestamps = false;

    protected $table = 'settings';
    protected $softDelete = false;
    
    protected $fillable = array('title', 'key', 'value', 'description');
}