<?php namespace App\Modules\Members\Internal\Validators;

use App\Internal\Validators\BaseValidator;
use App\Modules\Members\Internal\Sanitizers\MemberGroupSanitizer;

class MemberGroupValidator extends BaseValidator
{
    /**
     * Validation rules for this Validator.
     *
     * @var array
     */
    protected $rules = array(

        'create' => array(
            'name' => array('required'),
            'location' => array('required'),
            'training.monday.start' => 'date_format:H:i',
            'training.monday.end' => 'date_format:H:i',
            'training.tuesday.start' => 'date_format:H:i',
            'training.tuesday.end' => 'date_format:H:i',
            'training.wednesday.start' => 'date_format:H:i',
            'training.wednesday.end' => 'date_format:H:i',
            'training.thursday.start' => 'date_format:H:i',
            'training.thursday.end' => 'date_format:H:i',
            'training.friday.start' => 'date_format:H:i',
            'training.friday.end' => 'date_format:H:i',
            'training.saturday.start' => 'date_format:H:i',
            'training.saturday.end' => 'date_format:H:i',
            'training.sunday.start' => 'date_format:H:i',
            'training.sunday.end' => 'date_format:H:i',
        ),

        'update' => array(
            'name' => array('sometimes', 'required'),
            'location' => array('sometimes', 'required'),
            'training.monday.start' => 'date_format:H:i',
            'training.monday.end' => 'date_format:H:i',
            'training.tuesday.start' => 'date_format:H:i',
            'training.tuesday.end' => 'date_format:H:i',
            'training.wednesday.start' => 'date_format:H:i',
            'training.wednesday.end' => 'date_format:H:i',
            'training.thursday.start' => 'date_format:H:i',
            'training.thursday.end' => 'date_format:H:i',
            'training.friday.start' => 'date_format:H:i',
            'training.friday.end' => 'date_format:H:i',
            'training.saturday.start' => 'date_format:H:i',
            'training.saturday.end' => 'date_format:H:i',
            'training.sunday.start' => 'date_format:H:i',
            'training.sunday.end' => 'date_format:H:i',
        ),

    );

    protected $messages = array(
        'date_format' => 'Invalid time format.',
    );

    /**
     * Attach a default sanitizer to this
     * validator instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->attachSanitizer(new MemberGroupSanitizer());
    }
}