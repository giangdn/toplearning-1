<?php

namespace App\Providers;

use Config;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $mail = @\App\Config::getConfigEmail();

        if ($mail) //checking if table is not empty
        {
            $config = [
                'driver'     => $mail['email_driver'],
                'host'       => $mail['email_host'],
                'port'       => (int) $mail['email_port'],
                'from'       => [
                    'address'   => $mail['email_address'],
                    'name'      => $mail['email_from_name']
                ],
                'encryption' => $mail['email_encryption'],
                'username'   => $mail['email_user'],
                'password'   => $mail['email_password'],
            ];

            Config::set('mail', $config);
        }
    }
}
