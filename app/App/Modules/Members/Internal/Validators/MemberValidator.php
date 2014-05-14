<?php namespace App\Modules\Members\Internal\Validators;

use App\Internal\Validators\BaseValidator;
use App\Modules\Members\Internal\Sanitizers\MemberSanitizer;

class MemberValidator extends BaseValidator
{
    /**
     * Validation rules for this Validator.
     *
     * @var array
     */
    protected $rules = array(

        'create' => array(
            'first_name' => array('required'),
            'last_name' => array('required'),
            //'uid'    => array('required', 'unique:members,uid'),
            'dob'    => array('required', 'date'),
            'dos'    => array('required', 'date'),
            'doc'    => array('date'),
        ),

        'update' => array(
            'first_name' => array('required'),
            'last_name' => array('required'),
            //'uid'    => array('required', 'unique:members,uid,%s'),
            'dob'    => array('required', 'date'),
            'dos'    => array('required', 'date'),
            'doc'    => array('date'),
        ),

    );

    /**
     * Attach a default sanitizer to this
     * validator instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->attachSanitizer(new MemberSanitizer());
    }
}