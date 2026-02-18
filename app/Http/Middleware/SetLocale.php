<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('locale')
            ?? $request->getPreferredLanguage(config('ecommerce.locales', ['en']))
            ?? config('app.locale');

        $locale = in_array($locale, config('ecommerce.locales', ['en'])) ? $locale : 'en';

        app()->setLocale($locale);

        return $next($request);
    }
}
