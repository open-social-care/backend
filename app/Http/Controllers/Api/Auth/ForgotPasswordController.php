<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Mail\SendCodeResetPassword;
use App\Models\PasswordResetToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ForgotPasswordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/password/email",
     *     operationId="Password send recuperation email",
     *     tags={"Auth"},
     *     summary="User password send recuperation email",
     *     description="User password send recuperation email",
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
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Password recuperation send Successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="integer", example="200"),
     *              @OA\Property(property="message", type="string", example="Password recuperation send Successfully")
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
     *      ),
     * )
     */
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            PasswordResetToken::where('email', $request->email)->delete();

            $codeData = PasswordResetToken::create($request->data());

            Mail::to($request->email)->send(new SendCodeResetPassword($codeData->token));

            return response()->json(['status' => HttpResponse::HTTP_OK, 'message' => __('passwords.sent')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['status' => HttpResponse::HTTP_BAD_REQUEST, 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
