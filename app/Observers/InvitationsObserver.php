<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Observers;

use App\Models\Invitation;

class InvitationsObserver
{
    public function creating(Invitation $invitation)
    {
    }

    public function created(Invitation $invitation)
    {
        /**
         * @var Mail $mail
         */
        $mail = app('\App\Services\Mail');
        $mail->sendEmailInvitation($invitation);
    }

    public function updating(Invitation $invitation)
    {
    }

    public function updated(Invitation $invitation)
    {
    }

    public function saving(Invitation $invitation)
    {
    }

    public function saved(Invitation $invitation)
    {
    }

    public function deleting(Invitation $invitation)
    {
    }

    public function deleted(Invitation $invitation)
    {
    }

    public function restoring(Invitation $invitation)
    {
    }

    public function restored(Invitation $invitation)
    {
    }
}
