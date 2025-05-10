<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class SendWelcomeEmail
{
    public function handle(Registered $event): void
    {
        Mail::to($event->user)->send(new WelcomeMail($event->user));
    }
}
