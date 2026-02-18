<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SetApiLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Accept-Language', 'en');
        $locale = Str::before($locale, ',');
        $locale = Str::before($locale, ';');
        $locale = trim($locale);

        if (in_array($locale, config('ecommerce.locales', ['en']))) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
