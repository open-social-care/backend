<?php

namespace App\Http\Controllers\Auth;

use App\Models\PasswordResetTokens;
use App\Mail\SendCodeResetPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function __invoke(ForgotPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            PasswordResetTokens::where('email', $request->email)->delete();

            $codeData = PasswordResetTokens::create($request->data());

            Mail::to($request->email)->send(new SendCodeResetPassword($codeData->token));

            return response()->json(['message' => __('passwords.sent')], HttpResponse::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status);
        }
    }
}