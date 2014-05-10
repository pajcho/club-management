<?php namespace App\Internal\Sanitizers;

class ResultCategorySanitizer extends BaseSanitizer
{
    public function sanitize($data)
    {
        if(isset($data['name']))
            $data['name'] = trim($data['name']);

        return $data;
    }
}