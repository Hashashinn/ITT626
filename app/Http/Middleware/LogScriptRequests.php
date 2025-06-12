<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LogScriptRequests
{
    private function isJavaScriptRequest($request)
    {
        $requestedUrl = $request->getRequestUri();
        return Str::endsWith($requestedUrl, '.js');
    }

    public function handle($request, Closure $next)
    {
        if ($this->isJavaScriptRequest($request)) {
            $urlComponents = parse_url($request->getRequestUri());
            $query = $urlComponents['query'] ?? '';
            $version = explode('=', $query)[1] ?? 'unknown';

            Log::channel('daily')->info("JavaScript requested: " . $request->getRequestUri() . " (Version: $version)");
        }

        return $next($request);
    }
}