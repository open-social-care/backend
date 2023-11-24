<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetLinkRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(PasswordResetLinkRequest $request): JsonResponse
    {
        try {
            $request->validated();

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status != Password::RESET_LINK_SENT) {
                throw ValidationException::withMessages([
                    'email' => [__($status)],
                ]);
            }

            return response()->json(['status' => __($status)]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status);
        }
    }
}
