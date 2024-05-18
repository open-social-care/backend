<?php

namespace App\Http\Middleware;

use App\Enums\RolesEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class OnlySocialAssistantUser
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user->hasRoleByName(RolesEnum::SOCIAL_ASSISTANT->value) && $user->organizations->isNotEmpty()) {
            return $next($request);
        }

        return response()->json([
            'message' => __('messages.auth.access_denied'),
        ], HttpResponse::HTTP_FORBIDDEN);
    }
}
