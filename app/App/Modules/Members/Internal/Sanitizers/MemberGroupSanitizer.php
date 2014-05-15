<?php namespace App\Modules\Members\Internal\Sanitizers;

use App\Internal\Sanitizers\BaseSanitizer;

class MemberGroupSanitizer extends BaseSanitizer
{
    public function sanitize($data)
    {
        return $data;
    }
}