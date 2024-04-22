<?php

namespace App\Http\Controllers\Api\Manager;

use App\Actions\Manager\User\UserCreateAction;
use App\Actions\Manager\User\UserDisassociateFromOrganizationAction;
use App\Actions\Manager\User\UserUpdateAction;
use App\DTO\Shared\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Manager\UserCreateRequest;
use App\Http\Requests\Api\Manager\UserUpdateRequest;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Http\Resources\Api\Shared\UserListWithRolesResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ManagerUserController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/manager/users/{organization}",
     * operationId="ManagerGetUsers",
     * tags={"Manager/User"},
     * summary="Get a list of users",
     * description="Retrieve a list of users.",
     * security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The organization id",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
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
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Successful response"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="name", type="string", example="Teste"),
     *                 @OA\Property(property="email", type="string", example="teste@teste.com"),
     *                 @OA\Property(property="roles", type="array", @OA\Items(type="string", example="Gestor(a)")),
     *             )),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="to", type="integer"))
     *             )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Bad Request")
     *         )
     *     ),
     * )
     */
    public function index(Organization $organization): JsonResponse
    {
        $this->authorize('viewByOrganization', [User::class, $organization]);

        try {
            $query = User::query()
                ->whereHas('organizations', function ($query) use ($organization) {
                    return $query->where('organization_id', $organization->id);
                });

            if ($search = request()->get('q', null)) {
                $query->whereRaw("LOWER(name) LIKE '%' || LOWER(?) || '%'", [$search]);
            }

            $paginate = $query->paginate(30);

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => UserListWithRolesResource::collection($paginate),
                'pagination' => PaginationResource::make($paginate),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/manager/users/{organization}",
     *     operationId="ManagerCreateUser",
     *     tags={"Manager/User"},
     *     summary="Create a new user",
     *     description="Create a new user with the provided information in manager.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The organization id",
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
     *             @OA\Property(property="name", type="string", example="Teste"),
     *             @OA\Property(property="email", type="string", example="teste@teste.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="password_confirmation", type="string", example="12345678")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User created successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Bad Request")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unprocessable Entity"),
     *             @OA\Property(property="errors", type="object",
     *               @OA\Property(property="email", type="array", description="field with errors",
     *
     *                  @OA\Items(type="string", description="message error", example="Email is already in use")
     *            )
     *          ),
     *         )
     *     ),
     * )
     */
    public function store(UserCreateRequest $request, Organization $organization): JsonResponse
    {
        $this->authorize('createByOrganization', [User::class, $organization]);

        try {
            $data = $request->validated();

            $userDto = new UserDTO($data);
            UserCreateAction::execute($userDto, $organization);

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_create')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/manager/users/{user}",
     *     operationId="ManagerUpdateUser",
     *     tags={"Manager/User"},
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
     *             @OA\Property(property="name", type="string", example="Teste"),
     *             @OA\Property(property="email", type="string", example="teste@teste.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="password_confirmation", type="string", example="12345678")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Data updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Data updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Bad Request")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unprocessable Entity"),
     *             @OA\Property(property="errors", type="object",
     *               @OA\Property(property="email", type="array", description="field with errors",
     *
     *                  @OA\Items(type="string", description="message error", example="Email is already in use")
     *            )
     *          ),
     *         )
     *     ),
     * )
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        try {
            $data = $request->validated();

            $userDto = new UserDTO($data);
            UserUpdateAction::execute($userDto, $user);

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_update')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/manager/users/{user}/{organization}",
     *     operationId="ManagerDestroyUser",
     *     tags={"Manager/User"},
     *     summary="Destroy user",
     *     description="Destroy user with the provided id.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The organization id",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
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
     *         description="Data destroy successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Data destroy successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Bad Request")
     *         )
     *     ),
     * )
     */
    public function disassociateUserFromOrganization(User $user, Organization $organization): JsonResponse
    {
        $this->authorize('disassociateUserFromOrganization', $user);

        try {
            UserDisassociateFromOrganizationAction::execute($user, $organization);

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_destroy')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/manager/users/{user}",
     *     operationId="ManagerGetUser",
     *     tags={"Manager/User"},
     *     summary="Get user infos",
     *     description="Get user infos",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The user id for get",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Get data successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Get data successfully"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="name", type="string", example="Teste"),
     *                 @OA\Property(property="email", type="string", example="teste@teste.com")
     *             )),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Bad Request")
     *         )
     *     ),
     * )
     */
    public function getUser(User $user): JsonResponse
    {
        $this->authorize('viewByUserOrganizations', $user);

        try {
            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => UserListWithRolesResource::make($user),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
