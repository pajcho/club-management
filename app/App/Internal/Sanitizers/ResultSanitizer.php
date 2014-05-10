<?php namespace App\Internal\Sanitizers;

class ResultSanitizer extends BaseSanitizer
{
    public function sanitize($data)
    {
        if(isset($data['type']))
            $data['type'] = trim($data['type']);

        return $data;
    }
}