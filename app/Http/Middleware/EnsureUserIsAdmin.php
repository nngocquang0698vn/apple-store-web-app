<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class EnsureUserIsAdmin
{
    /**
     * @param  Closure(Request): HttpResponse  $next
     */
    public function handle(Request $request, Closure $next): HttpResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        if (! $user->canAccessAdmin()) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
