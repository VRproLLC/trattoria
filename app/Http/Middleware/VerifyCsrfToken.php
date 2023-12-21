<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/webhook/api_transport/get',
        'webhook.api_transport.get',
        '/webhook/fondy',
        '/order/pay-status',
        'order.pay-status',
        'save-token-os',
        '/save-token-os',
    ];
}
