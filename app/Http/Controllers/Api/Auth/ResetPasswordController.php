<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Models\PasswordResetToken;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ResetPasswordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/password/reset",
     *     operationId="Password reset password",
     *     tags={"Auth"},
     *     summary="User reset password",
     *     description="User reset password",
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
     *     ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Password reset Successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="integer", example="200"),
     *              @OA\Property(property="message", type="string", example="Password reset Successfully")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="integer", example="422"),
     *              @OA\Property(property="message", type="string", example="Unprocessable Entity")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="integer", example="400"),
     *              @OA\Property(property="message", type="string", example="Bad request")
     *          )
     *      )
     * )
     */
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $passwordReset = PasswordResetToken::firstWhere('token', $request->token);

            if ($passwordReset->isExpired()) {
                return response()->json([
                    'status' => HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
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
                'status' => HttpResponse::HTTP_OK,
                'message' => __('messages.auth.password.password_reset_success'),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['status' => HttpResponse::HTTP_BAD_REQUEST, 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
