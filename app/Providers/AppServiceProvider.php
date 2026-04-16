<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (in_array(config('app.name'), ['Laravel', ''], true)) {
            config(['app.name' => 'CollegeCare']);
        }

        if (in_array(config('mail.from.name'), ['Laravel', 'Example', ''], true)) {
            config(['mail.from.name' => 'CollegeCare']);
        }

        ResetPassword::toMailUsing(function (object $notifiable, string $token): MailMessage {
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $expireMinutes = (int) config('auth.passwords.'.config('auth.defaults.passwords').'.expire');
            $studentName = $notifiable->name ?? 'there';

            return (new MailMessage)
                ->subject('Reset your CollegeCare password')
                ->markdown('emails.auth.reset-password', [
                    'resetUrl' => $resetUrl,
                    'expireMinutes' => $expireMinutes,
                    'studentName' => $studentName,
                ]);
        });
    }
}
