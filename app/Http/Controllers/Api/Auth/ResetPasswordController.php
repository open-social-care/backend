<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Models\PasswordResetToken;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ResetPasswordController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/password/reset",
     * operationId="Password reset password",
     * tags={"Auth"},
     * summary="User reset password",
     * description="User reset password",
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  type="string",
     *                  default="123456",
     *                  description="token",
     *                  property="token"
     *              ),
     *              @OA\Property(
     *                  type="password",
     *                  default="123456",
     *                  description="password",
     *                  property="password"
     *              ),
     *              @OA\Property(
     *                  type="password",
     *                  default="123456",
     *                  description="password_confirmation",
     *                  property="password_confirmation"
     *              ),
     *          ),
     *
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *
     *            @OA\Schema(
     *               type="object",
     *               required={"token", "password", "password_confirmation"},
     *
     *               @OA\Property(property="token", type="text"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="password_confirmation", type="password"),
     *            ),
     *        ),
     *    ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Password reset Successfully",
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $passwordReset = PasswordResetToken::firstWhere('token', $request->token);

            if ($passwordReset->isExpired()) {
                return response()->json([
                    'message' => __('passwords.token_is_invalid'),
                ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user = User::firstWhere('email', $passwordReset->email);

            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            $passwordReset->delete();

            return response()->json([
                'message' => __('messages.auth.password.password_reset_success'),
            ], HttpResponse::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status);
        }
    }
}
