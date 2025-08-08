<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // إذا لم يكن المستخدم قد سجل دخوله، اتركه يكمل للمسار التالي
        // (سيتم منعه بواسطة middleware 'auth' القياسي في لارافيل)
        if (!Auth::check()) {
            return $next($request);
        }

        // احصل على المستخدم الحالي
        $user = Auth::user();

        // تحقق مما إذا كان المستخدم يمتلك أي من الصلاحيات المطلوبة
        foreach ($roles as $role) {
            if ($user->role === $role) {
                // إذا كان يمتلك الصلاحية، اسمح له بالمرور
                return $next($request);
            }
        }

        // إذا لم يكن يمتلك أي من الصلاحيات المطلوبة، امنعه من الوصول
        abort(403, 'UNAUTHORIZED ACTION.');
    }
}