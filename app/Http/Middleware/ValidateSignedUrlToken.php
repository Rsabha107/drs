<?php

namespace App\Http\Middleware;

use App\Models\SignedUrlToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSignedUrlToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if token parameter exists
        if (!$request->has('token')) {
            abort(403, 'Invalid or missing token.');
        }

        $token = $request->query('token');

        // Find the token in the database
        $urlToken = SignedUrlToken::where('token', $token)->first();

        if (!$urlToken) {
            abort(403, 'Invalid token.');
        }

        // Check if token is valid (not used and not expired)
        if (!$urlToken->isValid()) {
            if ($urlToken->used) {
                abort(403, 'This link has already been used.');
            }
            abort(403, 'This link has expired.');
        }

        // Attach token to request for later use
        $request->attributes->add(['signed_url_token' => $urlToken]);

        return $next($request);
    }
}
