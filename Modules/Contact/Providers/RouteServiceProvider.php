<?php

namespace Modules\Contact\Providers;

use Modules\Core\Providers\RoutingServiceProvider;

class RouteServiceProvider extends RoutingServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Contact\Http\Controllers\Api';

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

    protected function getWebRoute(): ?string
    {
        return null;
    }

    protected function getApiRoute(): ?string
    {
        return module_path('Contact', '/Routes/api.php');
    }
}
