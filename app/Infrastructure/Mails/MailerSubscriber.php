<?php

namespace App\Infrastructure\Mails;

use App\Domain\Product\PriceLoweredEvent;
use App\Infrastructure\Mails\Notifications\PriceLoweredNotification;
use App\Infrastructure\Mails\Notifications\PriceTrackerFailedNotification;
use App\Jobs\ProcessProduct;
use App\Models\User;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Events\JobFailed;

class MailerSubscriber
{
    private function onPriceLowered(PriceLoweredEvent $event)
    {
        User::first()->notify(new PriceLoweredNotification(
            productName: $event->product->name,
            url: $event->product->url,
            oldPrice: $event->product->current_price,
            newPrice: $event->price,
        ));
    }

    private function onJobFailed(JobFailed $event)
    {
        if (! ($event->job instanceof ProcessProduct)) {
            return;
        }
        User::first()->notify(new PriceTrackerFailedNotification(
            productName: $event->job->product->name,
            error: $event->exception->getMessage(),
        ));
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            PriceLoweredEvent::class,
            $this->onPriceLowered(...)
        );

        $events->listen(
            JobFailed::class,
            $this->onJobFailed(...)
        );

    }
}
