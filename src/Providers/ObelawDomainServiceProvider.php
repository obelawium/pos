<?php

declare(strict_types=1);

namespace Obelaw\Ium\Domain\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Obelaw\Ium\Domain\Services\DomainService;
use Obelaw\Ium\ObelawiumManager;

final class ObelawDomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/domain.php', 'ium.domain');

        ObelawiumManager::macro('domain', fn() =>app(DomainService::class));
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([
                __DIR__ . '/../../config/domain.php' => config_path('ium/domain.php'),
            ], 'ium-domain-config');
        }

        AboutCommand::add('Obelawium', fn() => ['Obelawium Domain' => '0.1.0']);
    }
}
