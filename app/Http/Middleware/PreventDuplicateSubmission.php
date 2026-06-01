<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventDuplicateSubmission
{
    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            return $next($request);
        }

        $token = $request->input('_submit_token');

        if ($token) {
            $sessionKey = '_submit_tokens';
            $tokens = session()->get($sessionKey, []);

            if (in_array($token, $tokens)) {
                return redirect()->back()->with('success', '请勿重复提交');
            }

            $tokens[] = $token;
            // Keep only last 20 tokens to avoid session bloat
            if (count($tokens) > 20) {
                $tokens = array_slice($tokens, -20);
            }
            session()->put($sessionKey, $tokens);
        }

        return $next($request);
    }
}
