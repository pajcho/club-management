<?php namespace App\Modules\History\Models;

use App\Models\BaseModel;

class History extends BaseModel {
    
    protected $table = 'history';
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
        $user_model = app('config')->get('auth.model');
        return $user_model::find($this->user_id);
    }
}