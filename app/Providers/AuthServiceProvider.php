<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email - WINWIN Makeup')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Terima kasih telah bergabung dengan WINWIN Makeup. Kami sangat senang menyambut Anda sebagai bagian dari komunitas kami.')
                ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda dan mengaktifkan akun pendaftaran Anda.')
                ->action('Verifikasi Email', $url)
                ->line('Jika Anda tidak merasa melakukan pendaftaran akun di WINWIN Makeup, abaikan saja email ini.')
                ->salutation('Salam hangat,' . "\r\n" . 'Tim WINWIN Makeup');
        });
    }
}
