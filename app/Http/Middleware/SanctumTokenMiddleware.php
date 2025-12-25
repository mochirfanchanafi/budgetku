<?php

namespace App\Http\Middleware;

// libs
    use Closure;
    use Illuminate\Http\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\Auth;
// ====

class SanctumTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $urlCurrent = \Request::fullUrl();
        // cek login status user
            if (!auth()->check()) {
                return redirect('/login');
            }
        // =====================
        // cek available sanctum token
            if (empty(Session::get('sanctum_token'))) {
                return redirect('/login');
            }
        // ===========================
        return $next($request);
    }
}
