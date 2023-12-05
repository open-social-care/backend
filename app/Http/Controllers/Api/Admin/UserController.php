<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\User\UserCreateAction;
use App\Actions\Admin\User\UserUpdateAction;
use App\DTO\Admin\UserDTO;
use App\Enums\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\Api\Admin\UserListResource;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Models\Organization;
use App\Models\Role;
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
     * operationId="AdminGetUsers",
     * tags={"Admin/User"},
     * summary="Get a list of users",
     * description="Retrieve a list of users.",
     * security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="The search query parameter",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
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
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
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
            ], HttpResponse::HTTP_OK);
        } catch (\Exception|NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/admin/users/create",
     *     operationId="GetAdminUserCreationData",
     *     tags={"Admin/User"},
     *     summary="Get admin user creation data",
     *     description="Retrieve data needed for creating a new user, with organizations and roles available for selection.",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Data retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="organizationsToSelect", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             )),
     *             @OA\Property(property="rolesToSelect", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             ))
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
    public function create(): JsonResponse
    {
        try {
            $organizationsToSelect = to_select(Organization::all());
            $rolesToSelect = to_select_by_enum(Role::all(), RolesEnum::class);

            return response()->json([
                'organizationsToSelect' => $organizationsToSelect,
                'rolesToSelect' => $rolesToSelect
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/admin/users",
     *     operationId="AdminCreateUser",
     *     tags={"Admin/User"},
     *     summary="Create a new user",
     *     description="Create a new admin user with the provided information.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="User data",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string"),
     *             @OA\Property(property="roles", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="organizations", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
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

    /**
     * @OA\Get(
     *     path="/api/admin/users/{user}/edit",
     *     operationId="GetAdminUserEditData",
     *     tags={"Admin/User"},
     *     summary="Get admin user edit data",
     *     description="Retrieve data needed for editing a user, with organizations, roles available for selection and user options selected.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="The user Id parameter for edit",
     *          required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="organizationsToSelect", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             )),
     *             @OA\Property(property="rolesToSelect", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             )),
     *             @OA\Property(property="organizationsSelected", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             )),
     *             @OA\Property(property="rolessSelected", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             ))
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
    public function edit(User $user): JsonResponse
    {
        try {
            $organizationsToSelect = to_select(Organization::all());
            $rolesToSelect = to_select_by_enum(Role::all(), RolesEnum::class);

            $organizationsSelected = to_select($user->organizations);
            $rolesSelected = to_select_by_enum($user->roles, RolesEnum::class);

            return response()->json([
                'organizationsToSelect' => $organizationsToSelect,
                'rolesToSelect' => $rolesToSelect,
                'organizationsSelected' => $organizationsSelected,
                'rolesSelected' => $rolesSelected,
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/admin/users/{user}",
     *     operationId="AdminUpdateUser",
     *     tags={"Admin/User"},
     *     summary="Update user",
     *     description="Update user with the provided information.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The user id for update",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="User data",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string"),
     *             @OA\Property(property="roles", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="organizations", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     * )
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        try {
            $data = $request->validated();

            $userDto = new UserDTO($data);
            UserUpdateAction::execute($userDto, $user);

            return response()->json(['message' => __('messages.common.success_update')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/users/{user}",
     *     operationId="AdminDestroyUser",
     *     tags={"Admin/User"},
     *     summary="Destroy user",
     *     description="Destroy user with the provided id.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The user id for destroy",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User destroy successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();

            return response()->json(['message' => __('messages.common.success_destroy')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
