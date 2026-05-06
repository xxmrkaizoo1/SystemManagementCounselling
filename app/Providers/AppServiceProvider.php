<?php

namespace App\Providers;


use App\Models\InboxNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        View::composer('*', static function ($view): void {
            $showInboxNotificationDot = false;

            if (Auth::check()) {
                $latestNotificationId = InboxNotification::query()
                    ->where('user_id', Auth::id())
                    ->max('id');

                $lastSeenNotificationId = (int) session('inbox_last_seen_notification_id', 0);
                $showInboxNotificationDot = $latestNotificationId !== null && (int) $latestNotificationId > $lastSeenNotificationId;
            }

            $view->with('showInboxNotificationDot', $showInboxNotificationDot);
        });
        ResetPassword::toMailUsing(function (object $notifiable, string $token): MailMessage {
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $expireMinutes = (int) config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
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
