<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class KeycloakAuthenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if ($token = $request->bearerToken()) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }
        
        return parent::handle($request, $next, ...$guards);
    }
}