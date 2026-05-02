<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XssSanitization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    // {
    //     // Skip sanitization for file upload routes
    //     if ($request->is('uploads/*') || $request->hasFile('qid_files') || $request->hasFile('file')) {
    //         return $next($request);
    //     }
        
    //     // Get only non-file input (skip uploaded files)
    //     $userInput = $request->except(array_keys($request->allFiles()));
        
    //     $allowedTags = '<p><b><i><u><ul><ol><li><br><strong><em><h1><h2><h3><h4><h5><h6><a><img><table><thead><tbody><tr><th><td>';
        
    //     array_walk_recursive($userInput, function (&$userInput) use ($allowedTags) {
    //         // Only sanitize strings, skip other data types
    //         if (is_string($userInput)) {
    //             $userInput = strip_tags($userInput, $allowedTags);
    //         }
    //     });
        
    //     $request->merge($userInput);

    //     return $next($request);
    // }
    {
        $userInput = $request->except(array_keys($request->allFiles()));

        array_walk_recursive($userInput, function (&$userInput) {
            if (is_string($userInput)) {
                $userInput = strip_tags($userInput);
            }
        });

        $request->merge($userInput);

        return $next($request);
    }
}
