<?php

namespace App\Http\Middleware;

use Closure;

class SuperuserMiddleware
{
    public function handle($request, Closure $next)
    {
        // 检查用户的 role 是否为 'Superuser'
        if ($request->user() && $request->user()->role === 'Superuser') {
            return $next($request);
        }

        // 如果不符合条件，重定向到登录页面
        return redirect('/login')->with('error', 'Unauthorized access.');
    }
}
