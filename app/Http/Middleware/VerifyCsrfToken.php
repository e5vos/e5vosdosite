<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * CSRF is intentionally disabled application-wide (stateless API consumed
     * by an external SPA). This class is referenced by config/sanctum.php so
     * the same exclusion applies to Sanctum's stateful CSRF check.
     *
     * @var array<int, string>
     */
    protected $except = [
        '*',
    ];
}
