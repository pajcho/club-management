<?php namespace App\Modules\Results\Internal\Validators;

use App\Internal\Validators\BaseValidator;
use App\Modules\Results\Internal\Sanitizers\ResultSanitizer;

class ResultValidator extends BaseValidator
{
    /**
     * Validation rules for this Validator.
     *
     * @var array
     */
    protected $rules = array(

        'create' => array(
            'member_id' => array('required'),
            'category_id' => array('required'),
            'year' => array('required'),
            'place' => array('required'),
            'type' => array('required'),
        ),

        'update' => array(
            'member_id' => array('required'),
            'category_id' => array('required'),
            'year' => array('required'),
            'place' => array('required'),
            'type' => array('required'),
        ),

    );

    /**
     * Attach a default sanitizer to this
     * validator instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->attachSanitizer(new ResultSanitizer());
    }
}