<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Admin\UserLoginResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="Login",
     *     tags={"Auth"},
     *     summary="User Login",
     *     description="User Login",
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
     *              @OA\Property(
     *                  type="string",
     *                  default="123456",
     *                  description="password",
     *                  property="password"
     *              )
     *          ),
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Login Successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="type", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Login Successfully"),
     *              @OA\Property(property="token", type="string", example="1|Lkhuda45dajdanfi45"),
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer", example="1"),
     *                  @OA\Property(property="name", type="string", example="Test"),
     *                  @OA\Property(property="email", type="string", example="test@test.com"),
     *                  @OA\Property(property="roles_ids", type="array", @OA\Items(type="integer", example="1")),
     *                  @OA\Property(property="organizations_ids", type="array", @OA\Items(type="integer", example="1")),
     *                  @OA\Property(property="subject_ref_by_organizations", type="array", @OA\Items(type="integer", example="subjects"))
     *              )),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="type", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Unauthorized")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="type", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Bad request")
     *          )
     *      ),
     * )
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $token = $request->user()->createToken('auth-token')->plainTextToken;

                return response()->json([
                    'type' => 'success',
                    'message' => __('messages.auth.login_success'),
                    'token' => $token,
                    'data' => UserLoginResource::make(auth()->user()),
                ], HttpResponse::HTTP_OK);
            }

            return response()->json([
                'type' => 'error',
                'message' => __('messages.auth.login_invalid'),
            ], HttpResponse::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => $e->getMessage(),
            ], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/logout",
     *      operationId="Logout",
     *      tags={"Auth"},
     *      summary="User Logout",
     *      description="User Logout",
     *      security={{"sanctum":{}}},
     *
     *      @OA\Response(
     *          response=200,
     *          description="Logout Successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="type", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Logout Successfully")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="type", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Bad request")
     *          )
     *      ),
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'type' => 'success',
                'message' => __('messages.auth.logout_success'),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => $e->getMessage(),
            ], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
