<?php

namespace Modules\Authorization\Providers;

use Modules\Core\Providers\RoutingServiceProvider;

class RouteServiceProvider extends RoutingServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Authorization\Http\Controllers';

    protected function getWebRoute(): ?string
    {
        return null;
    }

    protected function getApiRoute(): ?string
    {
        return module_path('Authorization', '/Routes/api.php');
    }
}
