<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        '/rfid-detect',
        '/clear-rfid-cache',
        '/get-latest-rfid'
    ];
}