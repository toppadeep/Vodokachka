<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/period',
        'api/rate',
        'api/bill',
        'api/resident',
        'api/pump',
        'api/resident/update/*',
        'api/period/update/*',
        'api/rate/update/*',
        'api/bill/update/*',
        'api/pump/update/*'
    ];
}
