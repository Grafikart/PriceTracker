<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\form;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new User';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (User::count() > 0) {
            $this->info(__('User already created'));

            return;
        }
        $data = form()
            ->text(__('Email'),
                required: true,
                validate: ['email' => 'required|email'],
                hint: __('This will be used to notify you when price drop and to login'),
                name: 'email'
            )
            ->password(__('Password'),
                required: true,
                validate: ['password' => 'required'],
                name: 'password'
            )
            ->submit();
        (new User)->forceFill([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ])->save();
        $this->info(__('User created with success'));
    }
}
