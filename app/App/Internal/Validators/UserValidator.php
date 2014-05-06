<?php namespace App\Internal\Validators;

use App\Internal\Sanitizers\UserSanitizer;

class UserValidator extends BaseValidator
{
    /**
     * Validation rules for this Validator.
     *
     * @var array
     */
    protected $rules = array(

        'create' => array(
            'username' => array('required', 'unique:users,username'),
            'email' => array('required', 'unique:users,email'),
            'first_name' => array('required'),
            'last_name' => array('required'),
            'password' => array('required'),
            'password_confirm' => array('required', 'same:password'),
            'type' => array('required', 'in:admin,trainer'),
            'groups' => array('required'),
        ),

        'update' => array(
            'username' => array('required', 'unique:users,username,%s'),
            'email' => array('required', 'unique:users,email,%s'),
            'first_name' => array('required'),
            'last_name' => array('required'),
            'password_confirm' => array('same:password'),
            'type' => array('required', 'in:admin,trainer'),
            'groups' => array('required'),
        ),

    );

    /**
     * Attach a default sanitizer to this
     * validator instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->attachSanitizer(new UserSanitizer());
    }
}