<?php namespace App\Internal\Validators;

use App\Internal\Sanitizers\MemberSanitizer;

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
            'dob'    => array('required', 'date'),
            'dos'    => array('required', 'date'),
        ),

        'update' => array(
            'first_name' => array('required'),
            'last_name' => array('required'),
            'dob'    => array('required', 'date'),
            'dos'    => array('required', 'date'),
        ),

    );

    /**
     * Attach a default sanitizer to this
     * validator instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->attachSanitizer(new MemberSanitizer);
    }
}