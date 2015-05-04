<?php namespace App\Modules\Results\Internal\Validators;

use App\Internal\Validators\BaseValidator;
use App\Modules\Results\Internal\Sanitizers\ResultCategorySanitizer;

class ResultCategoryValidator extends BaseValidator
{
    /**
     * Validation rules for this Validator.
     *
     * @var array
     */
    protected $rules = array(

        'create' => array(
            'name' => array('required'),
        ),

        'update' => array(
            'name' => array('required'),
        ),
    );

    /**
     * Attach a default sanitizer to this
     * validator instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->attachSanitizer(new ResultCategorySanitizer());
    }
}