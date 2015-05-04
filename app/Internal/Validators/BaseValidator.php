<?php namespace App\Internal\Validators;

use App\Internal\Sanitizers\BaseSanitizer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

abstract class BaseValidator
{
    /**
     * Data to validate
     *
     * @var array
     */
    protected $data;

    /**
     * A collection of validation errors.
     *
     * @var Collection
     */
    protected $errors;

    /**
     * An array of sanitizers to be executed
     * before the validation process.
     *
     * @var array
     */
    protected $sanitizers = [];

    /**
     * Validation rules for this Validator.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * An array of custom validation messages.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Set the intial errors collection.
     */
    public function __construct()
    {
        $this->errors = new Collection;
    }

    /**
     * Validate the provided data using the
     * internal rules array.
     *
     * @param  mixed $data
     * @param string $ruleset
     * @param null $exclude
     * @return bool
     */
    public function validate($data, $ruleset = 'create', $exclude = null)
    {
        $this->data = $data;

        // We allow collections, so transform to array.
        if ($data instanceof Collection) {
            $this->data = $this->data->toArray();
        }

        // Execute sanitizers over the data before validation.
        $this->runSanitizers();

        // Load the correct ruleset.
        $rules = $this->rules[$ruleset];

        // Exclude certain ID if required
        if($exclude) $this->attachExclude($rules, $exclude);

        // Create the validator instance and validate.
        $validator = Validator::make($this->data, $rules, $this->messages);
        if (!$result = $validator->passes()) {
            $this->errors = $validator->messages();
        }

        // Return the validation result.
        return $result;
    }

    /**
     * Attach exclude ID used when validating with unique rule
     *
     * @param $rules
     * @param $exclude
     */
    private function attachExclude(&$rules, $exclude)
    {
        // Add exclude part to rules, mainly used for unique rules
        foreach ($rules as $field => $rule)
        {
            if(is_array($rule))
            {
                foreach ($rule as $position => $single_rule)
                {
                    $rules[$field][$position] = sprintf($single_rule, $exclude);
                }
            }
            else
            {
                $rules[$field] = sprintf($rule, $exclude);
            }
        }
    }

    /**
     * Attach a sanitizer to this validation instance
     * to be executed before the validation process.
     *
     * @param  BaseSanitizer $sanitizer
     * @return BaseValidator
     */
    public function attachSanitizer(BaseSanitizer $sanitizer)
    {
        $this->sanitizers[] = $sanitizer;
        return $this;
    }

    /**
     * Execute all of our registered sanitizers
     * on the validation data.
     *
     * @return void
     */
    protected function runSanitizers()
    {
        foreach ($this->sanitizers as $sanitizer) {
            $this->data = $sanitizer->sanitize($this->data);
        }
    }

    /**
     * Return the error collection after a failed
     * validation attempt.
     *
     * @return Collection
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Return the data
     *
     * @return array
     */
    public function data()
    {
        return $this->data;
    }
}