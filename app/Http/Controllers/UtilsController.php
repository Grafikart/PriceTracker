<?php

namespace App\Http\Controllers;

use App\Infrastructure\Mails\Notifications\PriceLoweredNotification;
use App\Models\User;

class UtilsController
{
    public function mail()
    {
        User::first()->notify(
            new PriceLoweredNotification(
                'Demo',
                'https://grafikart.fr',
                300,
                200
            )
        );

        return 'ok';
    }
}
