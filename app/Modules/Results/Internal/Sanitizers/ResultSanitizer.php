<?php namespace App\Modules\Results\Internal\Sanitizers;

use App\Internal\Sanitizers\BaseSanitizer;

class ResultSanitizer extends BaseSanitizer
{
    public function sanitize($data)
    {
        if(isset($data['type']))
            $data['type'] = trim($data['type']);

        return $data;
    }
}