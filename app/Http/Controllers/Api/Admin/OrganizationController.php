<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\Organization\OrganizationCreateAction;
use App\Actions\Admin\Organization\OrganizationUpdateAction;
use App\DTO\Admin\OrganizationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\OrganizationAssociateUsersRequest;
use App\Http\Requests\Api\Admin\OrganizationRequest;
use App\Http\Resources\Api\Admin\OrganizationListResource;
use App\Http\Resources\Api\Admin\UserListResource;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class OrganizationController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/admin/organizations",
     * operationId="AdminGetOrganizations",
     * tags={"Admin/Organization"},
     * summary="Get a list of organizations",
     * description="Retrieve a list of organizations.",
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
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Successful response"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="name", type="string", example="Social Care"),
     *                 @OA\Property(property="document_type", type="string", example="cpf"),
     *                 @OA\Property(property="document", type="string", example="123.456.789-0")
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
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Bad Request")
     *         )
     *     ),
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $search = request()->get('q', null);
            $paginate = Organization::search($search)->paginate(30);

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => OrganizationListResource::collection($paginate),
                'pagination' => PaginationResource::make($paginate),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/admin/organizations",
     *     operationId="AdminCreateOrganization",
     *     tags={"Admin/Organization"},
     *     summary="Create a new Organization",
     *     description="Create a new Organization with the provided information.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Organization data",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="name", type="string", example="Social Care"),
     *             @OA\Property(property="phone", type="string", example="(42) 91234-5789", description="(00) 00000-0000"),
     *             @OA\Property(property="document_type", type="string", example="CPF", description="CNPJ or CPF"),
     *             @OA\Property(property="document", type="string", example="123.456.789-0", description="00.000.000/0000-00 / 000.000.000-00"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Organization created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Organization created successfully")
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
     *                @OA\Property(property="name", type="array", description="field with errors",
     *
     *                  @OA\Items(type="string", description="message error", example="The field name is required")
     *             )
     *           ),
     *         )
     *      ),
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
    public function store(OrganizationRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $dto = new OrganizationDTO($data);
            OrganizationCreateAction::execute($dto);

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_create')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/admin/organizations/{organization}",
     *     operationId="AdminUpdateOrganization",
     *     tags={"Admin/Organization"},
     *     summary="Update Organization",
     *     description="Update Organization with the provided information.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The organization id for update",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Organization data",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="name", type="string", example="Social Care"),
     *             @OA\Property(property="phone", type="string", example="(42) 91234-5789", description="(00) 00000-0000"),
     *             @OA\Property(property="document_type", type="string", example="CPF", description="CNPJ or CPF"),
     *             @OA\Property(property="document", type="string", example="123.456.789-0", description="00.000.000/0000-00 / 000.000.000-00"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Organization updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Organization updated successfully")
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
     *                @OA\Property(property="name", type="array", description="field with errors",
     *
     *                  @OA\Items(type="string", description="message error", example="The field name is required")
     *             )
     *           ),
     *         )
     *      ),
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
    public function update(OrganizationRequest $request, Organization $organization): JsonResponse
    {
        try {
            $data = $request->validated();

            $dto = new OrganizationDTO($data);
            OrganizationUpdateAction::execute($dto, $organization);

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_update')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/organizations/{organization}",
     *     operationId="AdminDestroyOrganization",
     *     tags={"Admin/Organization"},
     *     summary="Destroy organization",
     *     description="Destroy organization with the provided id.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The organization id for destroy",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Organization destroy successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Organization destroy successfully")
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
    public function destroy(Organization $organization): JsonResponse
    {
        try {
            $organization->delete();

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_destroy')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/admin/organizations/{organization}/associate-users",
     *     operationId="AdminOrganizationAssociateUsers",
     *     tags={"Admin/Organization"},
     *     summary="Associate users to organization",
     *     description="Associate users to organization with the provided information.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The organization id for associate users",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Organization associate data",
     *
     *         @OA\JsonContent(
     * *             type="object",
     * *
     *             @OA\Property(property="users", type="array", @OA\Items(type="integer", example="1")),
     * *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Updated successfully")
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
     *                @OA\Property(property="users", type="array", description="field with errors",
     *
     *                  @OA\Items(type="string", description="message error", example="The field users is required")
     *             )
     *           ),
     *         )
     *      ),
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
    public function associateUsersToOrganization(OrganizationAssociateUsersRequest $request, Organization $organization): JsonResponse
    {
        try {
            $data = $request->validated();
            $organization->users()->sync($data['users']);

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_update')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     * path="/api/admin/organizations/{organization}/get-users-by-role/{role}",
     * operationId="AdminGetOrganizationUsersByRole",
     * tags={"Admin/Organization"},
     * summary="Get a list of organization users by role",
     * description="Retrieve a list of organization users by role.",
     * security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The organization id for list users",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="role",
     *         in="path",
     *         description="The role for filter users (manager or social_assistant)",
     *         required=true,
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
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Bad Request"),
     *         )
     *     ),
     * )
     */
    public function getOrganizationUsersListByRole(Organization $organization, string $role): JsonResponse
    {
        try {
            $paginate = User::whereHas('organizations', function ($query) use ($organization) {
                return $query->where('organizations.id', $organization->id);
            })
                ->whereHas('roles', function ($query) use ($role) {
                    return $query->where('roles.name', $role);
                })
                ->paginate(30);

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => UserListResource::collection($paginate),
                'pagination' => PaginationResource::make($paginate),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
