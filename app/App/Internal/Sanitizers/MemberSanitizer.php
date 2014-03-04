<?php namespace App\Internal\Sanitizers;

class MemberSanitizer extends BaseSanitizer
{
    public function sanitize($data)
    {
        if(isset($data['first_name']))
            $data['first_name'] = ucwords($data['first_name']);
        
        if(isset($data['last_name']))
            $data['last_name'] = ucwords($data['last_name']);

        return $data;
    }
}