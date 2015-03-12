<?php namespace App\Services\Mailers;

use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

abstract class Mailer {

    // Subject of email
    public $subject;
    
    // View file to use
    public $view;
    
    // Email data to use
    public $data;
    
    public function sendTo(User $user)
    {
        // Log sending of emails so we can easily check later if email is being sent
        Log::info('Sending email to: ' . $user->email . ' with BCC of: ' . implode(', ', Config::get('club-management.admin_emails', array())));

        // Send actual email
        $subject = $this->subject;

        return Mail::queue($this->view, $this->data, function($message) use ($user, $subject){
            $message->to($user->email)
                    ->bcc(Config::get('club-management.admin_emails', array()))
                    ->subject($subject);
        });
    }
}
