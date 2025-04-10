<?php

namespace App\Infrastructure\Mails\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PriceTrackerFailedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $productName,
        private readonly string $error
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
                    __('Error fetching the new price for "%s"'),
                    $this->productName,
                ))
            ->line(
                sprintf(
                    __('Error : %s'),
                    $this->error,
                ));
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
