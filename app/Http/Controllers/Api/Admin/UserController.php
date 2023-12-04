<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\User\UserCreateAction;
use App\DTO\Admin\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\Api\Admin\UserListResource;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UserController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/admin/users",
     * operationId="GetUsers",
     * tags={"Admin"},
     * summary="Get a list of users",
     * description="Retrieve a list of users.",
     * security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="The search query parameter",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="links", type="object")
     *             )),
     *             @OA\Property(property="pagination", type="object",
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="from", type="integer"),
     *             @OA\Property(property="to", type="integer"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $search = request()->get('q', null);
            $paginate = User::search($search)->paginate(30);

            return response()->json([
                'data' => UserListResource::collection($paginate),
                'pagination' => PaginationResource::make($paginate),
            ]);
        } catch (\Exception|NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

//    public function create()
//    {
//        try {
//
//        } catch (\Exception $e) {
//            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
//        }
//    }

    /**
     * @OA\Post(
     *     path="/api/admin/users",
     *     operationId="CreateAdminUser",
     *     tags={"Admin"},
     *     summary="Create a new admin user",
     *     description="Create a new admin user with the provided information.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string"),
     *             @OA\Property(property="roles", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="organizations", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     * )
     */
    public function store(UserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $userDto = new UserDTO($data);
            UserCreateAction::execute($userDto);

            return response()->json(['message' => __('messages.common.success_create')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
