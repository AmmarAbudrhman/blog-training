<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log Request
        Log::info('Incoming Request', [
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'body' => $request->all(),
        ]);

        $response = $next($request);

        $status = $response->getStatusCode();
        $context = ['status' => $status];

        if ($status >= 500) {
            $context['content'] = $response->getContent();
            Log::error('Outgoing Server Error', $context);
        } elseif ($status >= 400) {
            $context['content'] = $response->getContent();
            Log::warning('Outgoing Client Error', $context);
        } else {
            Log::info('Outgoing Response', $context);
        }

        return $response;
    }
}
