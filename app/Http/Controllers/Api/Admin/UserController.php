<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\User\UserCreateAction;
use App\Actions\Admin\User\UserUpdateAction;
use App\DTO\Admin\UserDTO;
use App\Enums\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UserRequest;
use App\Http\Resources\Api\Admin\UserListResource;
use App\Http\Resources\Api\Admin\UserResource;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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
     *             @OA\Property(property="status", type="integer"),
     *             @OA\Property(property="message", type="string"),
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
     *             @OA\Property(property="status", type="integer"),
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
        } catch (\Exception $e) {
            return response()->json(['status' => HttpResponse::HTTP_BAD_REQUEST, 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
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
     *             @OA\Property(property="name", type="string", example="Teste"),
     *             @OA\Property(property="email", type="string", example="teste@teste.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="password_confirmation", type="string", example="12345678"),
     *             @OA\Property(property="roles", type="array", @OA\Items(type="integer", description="id of roles", example="2")),
     *             @OA\Property(property="organizations", type="array", @OA\Items(type="integer", description="id of organizations", example="3"))
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="integer", example="200"),
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
     *             @OA\Property(property="status", type="integer", example="400"),
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
     *             @OA\Property(property="status", type="integer", example="422"),
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
    public function store(UserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $userDto = new UserDTO($data);
            UserCreateAction::execute($userDto);

            return response()->json(['status' => HttpResponse::HTTP_OK, 'message' => __('messages.common.success_create')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['status' => HttpResponse::HTTP_BAD_REQUEST, 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
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
     *             @OA\Property(property="name", type="string", example="Teste"),
     *             @OA\Property(property="email", type="string", example="teste@teste.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="password_confirmation", type="string", example="12345678"),
     *             @OA\Property(property="roles", type="array", @OA\Items(type="integer", description="id of roles", example="2")),
     *             @OA\Property(property="organizations", type="array", @OA\Items(type="integer", description="id of organizations", example="3"))
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Data updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="integer", example="200"),
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
     *             @OA\Property(property="status", type="integer", example="400"),
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
     *             @OA\Property(property="status", type="integer", example="422"),
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
    public function update(UserRequest $request, User $user): JsonResponse
    {
        try {
            $data = $request->validated();

            $userDto = new UserDTO($data);
            UserUpdateAction::execute($userDto, $user);

            return response()->json(['status' => HttpResponse::HTTP_OK, 'message' => __('messages.common.success_update')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['status' => HttpResponse::HTTP_BAD_REQUEST, 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
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
     *         description="Data destroy successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="integer", example="200"),
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
     *             @OA\Property(property="status", type="integer", example="400"),
     *             @OA\Property(property="message", type="string", example="Bad Request")
     *         )
     *     ),
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();

            return response()->json(['status' => HttpResponse::HTTP_OK, 'message' => __('messages.common.success_destroy')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['status' => HttpResponse::HTTP_BAD_REQUEST, 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/admin/users/form-infos",
     *     operationId="GetAdminUserFormData",
     *     tags={"Admin/User"},
     *     summary="Get form data info for user creation and updation",
     *     description="Retrieve data needed for creating and updation a user, with organizations and roles available for selection.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Get data successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="message", type="string", example="Get data successfully"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="organizationsToSelect", type="array", @OA\Items(
     *                      @OA\Property(property="id", type="integer", example="1"),
     *                      @OA\Property(property="name", type="string", example="Social Care")
     *                 )),
     *                 @OA\Property(property="rolesToSelect", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer", example="2"),
     *                     @OA\Property(property="name", type="string", example="Gestor(a)")
     *                 )),
     *         )),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="integer", example="400"),
     *             @OA\Property(property="message", type="string", example="Bad Request")
     *         )
     *     ),
     * )
     */
    public function formInfos(): JsonResponse
    {
        try {
            $organizationsToSelect = to_select(Organization::all());
            $rolesToSelect = to_select_by_enum(Role::all(), RolesEnum::class);

            return response()->json([
                'status' => HttpResponse::HTTP_OK,
                'message' => __('messages.common.success_view'),
                'data' => [
                    'organizationsToSelect' => $organizationsToSelect,
                    'rolesToSelect' => $rolesToSelect,
                ],
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['status' => HttpResponse::HTTP_BAD_REQUEST, 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/admin/users/{user}",
     *     operationId="AdminGetUser",
     *     tags={"Admin/User"},
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
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="message", type="string", example="Get data successfully"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="name", type="string", example="Teste"),
     *                 @OA\Property(property="email", type="string", example="teste@teste.com"),
     *                 @OA\Property(property="roles_selected", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer", example="1"),
     *                     @OA\Property(property="name", type="string", example="Gestor(a)")
     *                 )),
     *                 @OA\Property(property="organizations_selected", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer", example="2"),
     *                     @OA\Property(property="name", type="string", example="Social Care")
     *                 )),
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
     *             @OA\Property(property="status", type="integer", example="400"),
     *             @OA\Property(property="message", type="string", example="Bad Request")
     *         )
     *     ),
     * )
     */
    public function getUser(User $user): JsonResponse
    {
        try {
            return response()->json([
                'status' => HttpResponse::HTTP_OK,
                'message' => __('messages.common.success_view'),
                'data' => UserResource::make($user),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['status' => HttpResponse::HTTP_BAD_REQUEST, 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
