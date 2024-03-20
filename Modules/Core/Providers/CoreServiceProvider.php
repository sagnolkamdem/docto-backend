<?php

namespace Modules\Core\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Modules\Core\Console\MakeFilterCommand;

class CoreServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Core';

    protected string $moduleNameLower = 'core';

    public function boot(): void
    {
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->registerPasswordValidation();

        $this->bootDateConfiguration();
    }

    public function register(): void
    {
        $this->app->register(RoutingServiceProvider::class);
        $this->registerCommands();
    }

    public function registerCommands()
    {
        $this->commands([
            MakeFilterCommand::class,
        ]);
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path('modules/' . $this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    protected function registerPasswordValidation(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8);

            return $this->app->environment('production')
                ? $rule->mixedCase()->uncompromised()
                : $rule;
        });
    }

    protected function bootDateConfiguration(): void
    {
        // setLocale for php. Enables ->formatLocalized() with localized values for dates.
        setlocale(LC_TIME, config('app.locale'));

        // Set the default timezone
        date_default_timezone_set(config('app.timezone'));

        // setLocale to use Carbon source locales. Enables diffForHumans() localized.
        Carbon::setLocale(config('app.locale'));
    }
}
