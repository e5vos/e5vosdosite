<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasE5Code
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->e5code) {
            return redirect()->route('studentcode', ['next' => $request->path()]);
        }

        return $next($request);
    }
}
