<?php

namespace App\Http\Middleware;

use App\Enums\RolesEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class OnlyAdminUser
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user->hasRoleByName(RolesEnum::ADMIN->value) && $user->organizations->isEmpty()) {
            return $next($request);
        }

        return response()->json([
            'type' => 'error',
            'message' => __('messages.auth.access_denied'),
        ], HttpResponse::HTTP_FORBIDDEN);
    }
}
