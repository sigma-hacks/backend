<?php

namespace App\Http\Middleware;

use App\Helpers\MainHelper;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;

class ApiIsRoleMiddleware extends AbstractMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Route::middleware(['roles:admin,user, employer, guest, partner'])
        $roleId = auth()->user()->role_id;
        if ($roleId == User::ROLE_ADMIN) {
            return $next($request);
        }

        $bookRoles = [
            "admin" => User::ROLE_ADMIN,
            'user' => User::ROLE_USER,
            'employer' => User::ROLE_EMPLOYEE,
            'guest' => User::ROLE_GUEST,
            'partner' => User::ROLE_PARTNER
        ];

        $validateRoles = [];

        foreach ($roles as $key => $value) {
            if (isset($bookRoles[$value])) {
                $validateRoles[$bookRoles[$value]] = true;
            }
        }

        if (isset($validateRoles[$roleId])) {
            return $next($request);
        }

        return $this->forbidden();
    }
}
