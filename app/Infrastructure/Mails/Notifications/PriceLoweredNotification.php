<?php

namespace App\Infrastructure\Mails\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Number;

class PriceLoweredNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $productName,
        private readonly string $url,
        private readonly int $oldPrice,
        private readonly int $newPrice
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line(
                sprintf(
                    __('The product "%s" has a new price %s instead of %s'),
                    $this->productName,
                    Number::currency($this->newPrice / 100, in: 'EUR'),
                    Number::currency($this->oldPrice / 100, in: 'EUR')
                ))
            ->action(__('View product'), $this->url);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
