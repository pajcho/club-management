<?php namespace App\Modules\Users\Models;

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
        return '<strong>' . link_to_route('user.show', $this->full_name, $this->id) . '</strong>';
    }

    public $timestamps = true;

    protected $table = 'users';
    protected $softDelete = false;

    protected $hidden = ['password'];
    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'phone', 'address', 'notes', 'password', 'type'];
    protected $appends = ['full_name', 'group_ids', 'gravatar'];

    public function groups()
    {
        return $this->belongsToMany('App\Modules\Members\Models\MemberGroup', 'users_groups', 'user_id', 'group_id')->withTimestamps();
    }

    public function data()
    {
        return $this->hasMany('App\Modules\Users\Models\UserGroupData');
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
     * Get gravatar image path generated from users email
     *
     * @return string
     */
    public function getGravatarAttribute()
    {
        return "//www.gravatar.com/avatar/" . md5(strtolower(trim($this->attributes['email']))) . "?d=monsterid&s=80";
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