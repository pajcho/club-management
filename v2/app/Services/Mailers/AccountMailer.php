<?php namespace App\Services\Mailers;

class AccountMailer extends Mailer {

    public function forgotPassword($data = array())
    {
        $this->subject  = 'Account Password Recovery';
        $this->view     = 'emails.forgot-password';
        $this->data     = $data;
        
        return $this;
    }
}
