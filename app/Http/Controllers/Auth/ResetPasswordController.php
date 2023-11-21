<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetTokens;
use Illuminate\Foundation\Auth\User;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Http\JsonResponse;

class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $passwordReset = PasswordResetTokens::firstWhere('token', $request->token);

            if ($passwordReset->isExpire()) {
                return response()->json([
                    'message' => __('passwords.token_is_invalid')
                ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user = User::firstWhere('email', $passwordReset->email);

            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            $passwordReset->delete();

            return response()->json([
                'message' => __('messages.auth.password.password_reset_success')
            ], HttpResponse::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status);
        }
    }
}