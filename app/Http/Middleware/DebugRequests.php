<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugRequests
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->path() === 'playlists/search/spotify') {
            Log::info('Spotify search request received', [
                'query' => $request->get('q'),
                'user_id' => auth()->id(),
                'headers' => $request->headers->all(),
                'method' => $request->method()
            ]);
        }

        $response = $next($request);

        if ($request->path() === 'playlists/search/spotify') {
            Log::info('Spotify search response sent', [
                'status' => $response->status(),
                'content' => $response->getContent()
            ]);
        }

        return $response;
    }
}
