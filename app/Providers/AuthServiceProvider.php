<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Organization' => 'App\Policies\OrganizationPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\FormTemplate' => 'App\Policies\FormTemplatePolicy',
        'App\Models\Subject' => 'App\Policies\SubjectPolicy',
        'App\Models\FormAnswer' => 'App\Policies\FormAnswerPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
