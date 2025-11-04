<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MasterRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * 管理者（rank が master）かどうかを判定するミドルウェア
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ユーザーがログインしていない場合はログインページにリダイレクト
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // ユーザーのrankがmasterでない場合はアクセス拒否
        if (Auth::user()->rank !== 'master') {
            abort(403, 'Access denied. Master role required.');
        }

        return $next($request);
    }
}
