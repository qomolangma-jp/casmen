<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMasterRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 認証されていない場合はログインページへ
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // rank が 'master' でない場合は403エラー
        if (Auth::user()->rank !== 'master') {
            abort(403, 'マスター権限が必要です。');
        }

        return $next($request);
    }
}
