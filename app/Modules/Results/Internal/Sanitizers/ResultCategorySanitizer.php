<?php namespace App\Modules\Results\Internal\Sanitizers;

use App\Internal\Sanitizers\BaseSanitizer;

class ResultCategorySanitizer extends BaseSanitizer
{
    public function sanitize($data)
    {
        if(isset($data['name']))
            $data['name'] = trim($data['name']);

        return $data;
    }
}