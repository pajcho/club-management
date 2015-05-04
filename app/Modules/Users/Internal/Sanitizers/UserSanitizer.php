<?php namespace App\Modules\Users\Internal\Sanitizers;

use App\Internal\Sanitizers\BaseSanitizer;

class UserSanitizer extends BaseSanitizer
{
    public function sanitize($data)
    {
        if(isset($data['first_name']))
            $data['first_name'] = ucwords(trim($data['first_name']));
        
        if(isset($data['last_name']))
            $data['last_name'] = ucwords(trim($data['last_name']));

        // Remove password field if it is blank. That means
        // that user don't want to change password
        if(isset($data['password']))
        {
            $data['password'] = trim($data['password']);
            if(empty($data['password'])) unset($data['password']);
        }

        return $data;
    }
}