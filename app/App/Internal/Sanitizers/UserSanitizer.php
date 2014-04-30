<?php namespace App\Internal\Sanitizers;

class UserSanitizer extends BaseSanitizer
{
    public function sanitize($data)
    {
        if(isset($data['first_name']))
            $data['first_name'] = ucwords(trim($data['first_name']));
        
        if(isset($data['last_name']))
            $data['last_name'] = ucwords(trim($data['last_name']));

        return $data;
    }
}