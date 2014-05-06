<?php

use App\Internal\HistorableTrait;
use App\Models\BaseModel;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Support\Facades\Hash;

class User extends BaseModel implements UserInterface, RemindableInterface {

    /** History */
    use HistorableTrait;
    public function historyTable()
    {
        return 'user';
    }

    public function historyTitle()
    {
        return $this->full_name;
    }

    public $timestamps = true;

    protected $table = 'users';
    protected $softDelete = false;

    protected $hidden = array('password');
    protected $fillable = array('first_name', 'last_name', 'username', 'email', 'password', 'type');
    protected $appends = array('full_name', 'group_ids');

    public function groups()
    {
        return $this->belongsToMany('App\Models\MemberGroup', 'users_groups', 'user_id', 'group_id')->withTimestamps();
    }

    /**
     * Hash password automaticaly when creating user
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Check to see if user is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->type == 'admin';
    }

    /**
     * Check to see if user is trainer
     *
     * @return bool
     */
    public function isTrainer()
    {
        return $this->type == 'trainer';
    }

    /**
     * Get user full name
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    /**
     * Get ids of all groups user is in
     * Used mainly for select boxes
     *
     * @return string
     */
    public function getGroupIdsAttribute()
    {
        return $this->groups()->get()->lists('id');
    }

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

}