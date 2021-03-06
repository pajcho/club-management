<?php namespace App\Modules\Users\Internal\Validators;

use App\Internal\Validators\BaseValidator;
use App\Modules\Users\Internal\Sanitizers\UserSanitizer;

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
            'email' => array('unique:users,email'),
            'first_name' => array('required'),
            'last_name' => array('required'),
            'password' => array('required'),
            'password_confirm' => array('required', 'same:password'),
            'type' => array('required', 'in:admin,trainer')
        ),

        'update' => array(
            'username' => array('required', 'unique:users,username,%s'),
            'email' => array('unique:users,email,%s'),
            'first_name' => array('required'),
            'last_name' => array('required'),
            'password_confirm' => array('same:password'),
            'type' => array('in:admin,trainer'),
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
