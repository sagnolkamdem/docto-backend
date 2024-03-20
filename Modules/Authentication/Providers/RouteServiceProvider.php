<?php

namespace Modules\Authentication\Providers;

use Modules\Core\Providers\RoutingServiceProvider;

class RouteServiceProvider extends RoutingServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Authentication\Http\Controllers';

    protected function getWebRoute(): ?string
    {
        return module_path('Authentication', '/Routes/web.php');
    }

    protected function getApiRoute(): ?string
    {
        return module_path('Authentication', '/Routes/api.php');
    }
}
