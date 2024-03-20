<?php

namespace Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

abstract class RoutingServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Core\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }

    abstract protected function getWebRoute(): ?string;

    abstract protected function getApiRoute(): ?string;

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(): void
    {
        $this->loadApiRoute();
        $this->loadWebRoutes();

        $this->mapApiRoutes();
    }

    /**
     * Define the "api" routes for the Core Module.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    public function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware(['api', 'auth:sanctum'])
            ->namespace($this->moduleNamespace)
            ->group(module_path('Core', './Routes/api.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function loadWebRoutes(): void
    {
        $web = $this->getWebRoute();

        if ($web && file_exists($web)) {
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group($web);
        }
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function loadApiRoute(): void
    {
        $api = $this->getApiRoute();

        if ($api && file_exists($api)) {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group($api);
        }
    }
}
