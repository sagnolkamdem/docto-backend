<?php

namespace Modules\Authentication\Auth;

use Dingo\Api\Contract\Auth\Provider;
use Dingo\Api\Routing\Route;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class SanctumProvider implements Provider
{
    /**
     * Illuminate authentication manager.
     *
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;

    /**
     * Create a new basic provider instance.
     *
     * @param \Illuminate\Auth\AuthManager $auth
     *
     * @return void
     */
    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth->guard('web');
    }

    /**
     * Authenticate request with Basic.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Dingo\Api\Routing\Route $route
     *
     * @return mixed
     */
    public function authenticate(Request $request, Route $route)
    {
        if ($user = $this->auth->user()) {
            return $user;
        }

        throw new UnauthorizedHttpException(
            'Bearer',
            __('Unauthenticated')
        );
    }
}
