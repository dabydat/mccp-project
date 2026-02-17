<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Domain\Repositories\MessageRepository::class, \App\Infrastructure\Persistence\EloquentMessageRepository::class);
        $this->app->bind(\App\Domain\Repositories\DeliveryLogRepository::class, \App\Infrastructure\Persistence\EloquentDeliveryLogRepository::class);
        $this->app->bind(\App\Domain\Services\AIService::class, \App\Infrastructure\AI\GeminiAIService::class);

        $this->app->bind(\App\Application\ProcessAndDistributeContent::class, function ($app) {
            return new \App\Application\ProcessAndDistributeContent(
                $app->make(\App\Domain\Repositories\MessageRepository::class),
                $app->make(\App\Domain\Repositories\DeliveryLogRepository::class),
                $app->make(\App\Domain\Services\AIService::class),
                [
                    new \App\Infrastructure\Channels\EmailChannel(),
                    new \App\Infrastructure\Channels\SlackChannel(),
                    new \App\Infrastructure\Channels\SmsLegacyChannel(),
                ]
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
