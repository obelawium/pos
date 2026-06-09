<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Obelaw\Ium\Pos\Services\PosService;
use Obelaw\Ium\ObelawiumManager;

final class ObelawPOSTerviceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/pos.php',
            'ium.pos',
        );

        ObelawiumManager::macro('pos', fn() => app(PosService::class));
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([
                __DIR__ . '/../../config/pos.php' => config_path('ium/pos.php'),
            ], 'ium-pos-config');
        }

        AboutCommand::add('Obelawium', fn() => ['Obelawium POS' => '0.1.0']);
    }
}
