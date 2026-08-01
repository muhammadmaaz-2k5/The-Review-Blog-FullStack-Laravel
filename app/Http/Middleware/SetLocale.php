<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session, or default to 'en'
        $locale = session('locale', config('app.locale', 'en'));
        
        // Validate locale
        $allowedLocales = ['en', 'hi'];
        if (!in_array($locale, $allowedLocales)) {
            $locale = 'en';
        }

        // Set the application locale
        App::setLocale($locale);

        return $next($request);
    }
}

