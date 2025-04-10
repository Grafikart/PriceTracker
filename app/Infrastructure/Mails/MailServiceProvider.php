<?php

namespace App\Infrastructure\Mails;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::subscribe(MailerSubscriber::class);
    }
}
