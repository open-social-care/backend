<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Mail\SendCodeResetPassword;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ForgotPasswordController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/password/email",
     * operationId="Password send recuperation email",
     * tags={"Auth"},
     * summary="User password send recuperation email",
     * description="User password send recuperation email",
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  type="string",
     *                  default="example@example.com",
     *                  description="email",
     *                  property="email"
     *              ),
     *          ),
     *
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *
     *            @OA\Schema(
     *               type="object",
     *               required={"email"},
     *
     *               @OA\Property(property="email", type="text"),
     *            ),
     *        ),
     *    ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Password recuperation send Successfully",
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function __invoke(ForgotPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            PasswordResetToken::where('email', $request->email)->delete();

            $codeData = PasswordResetToken::create($request->data());

            Mail::to($request->email)->send(new SendCodeResetPassword($codeData->token));

            return response()->json(['message' => __('passwords.sent')], HttpResponse::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status);
        }
    }
}
