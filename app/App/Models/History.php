<?php namespace App\Models;

use Illuminate\Support\Facades\Config;

class History extends BaseModel {
    
    public $timestamps = true;

    protected $table = 'history';
    protected $softDelete = false;
    
    protected $fillable = array('historable_type', 'historable_id', 'user_id', 'message');

    public function historable()
    {
        $this->morphTo();
    }

    /**
     * User Responsible
     * @return User user responsible for the change
     */
    public function user()
    {
        $user_model = Config::get('auth.model');
        return $user_model::find($this->user_id);
    }
}