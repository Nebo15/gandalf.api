<?php

namespace App\Services;

use Postmark\PostmarkClient;

class Mail
{

    private $postmark;

    public function __construct()
    {
        $this->postmark = new PostmarkClient(config('services.postmark.token'));
    }

    public function sendEmailConfirmation($email, $code, $name)
    {
        if (false == config('services.email.enabled')) {
            return null;
        }

        $this->postmark->sendEmailWithTemplate(
            config('services.postmark.sender'),
            $email,
            config('services.postmark.templates.welcome'),
            [
                'product_name' => 'Gandalf',
                'name' => $name,
                'action_url' => str_replace('{code}', $code, env('services.link.confirmation_email')),
                'username' => $name,
            ]
        );
    }

    public function sendRecoveryPassword($email, $code, $user)
    {
        if (false == config('services.email.enabled')) {
            return null;
        }

        $this->postmark->sendEmailWithTemplate(
            config('services.postmark.sender'),
            $email,
            config('services.postmark.templates.reset_password'),
            [
                'product_name' => 'Gandalf',
                'name' => $user->username,
                'action_url' => str_replace('{code}', $code, env('services.link.reset_password')),
                'username' => $user->username,
            ]
        );
    }

    public function sendEmailInvitation($invitation)
    {
        if (false == config('services.email.enabled')) {
            return null;
        }

        $this->postmark->sendEmailWithTemplate(
            config('services.postmark.sender'),
            $invitation->email,
            config('services.postmark.templates.invite'),
            [
                'product_name' => 'Gandalf',
                'name' => $invitation->email,
                'action_url' => env('services.link.invite'),
            ]
        );
    }
}
