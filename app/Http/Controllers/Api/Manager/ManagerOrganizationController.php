<?php

namespace App\Http\Controllers\Api\Manager;

use App\Actions\Manager\Organization\OrganizationUpdateAction;
use App\DTO\Manager\OrganizationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Manager\OrganizationUpdateRequest;
use App\Http\Resources\Api\Manager\UserListResource;
use App\Http\Resources\Api\Shared\OrganizationResource;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ManagerOrganizationController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/manager/organizations/{organization}",
     * operationId="ManagerGetOrganization",
     * tags={"Manager/Organization"},
     * summary="Get organization",
     * description="Retrieve organization show.",
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
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *              @OA\Property(property="type", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Successful response"),
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer", example="1"),
     *                  @OA\Property(property="name", type="string", example="Social Care"),
     *                  @OA\Property(property="document_type", type="string", example="cpf"),
     *                  @OA\Property(property="document", type="string", example="123.456.789-0")
     *              ))
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
     *         response=403,
     *         description="Forbidden",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     * )
     */
    public function show(Organization $organization): JsonResponse
    {
        $this->authorize('view', $organization);

        try {
            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => OrganizationResource::make($organization),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/manager/organizations-update/{organization}",
     *     operationId="ManagerUpdateOrganization",
     *     tags={"Manager/Organization"},
     *     summary="Update Organization",
     *     description="Update Organization with the provided information.",
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
     *         description="Organization data",
     *
     *         @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="phone", type="string", description="(00) 0000-0000"),
     *              @OA\Property(property="document_type", type="string", description="CNPJ/CPF"),
     *              @OA\Property(property="document", type="string", description="00.000.000/0000-00 / 000.000.000-00"),
     *          )
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
     *         response=403,
     *         description="Forbidden",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     * )
     */
    public function update(OrganizationUpdateRequest $request, Organization $organization): JsonResponse
    {
        $this->authorize('update', $organization);

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
     * @OA\Get(
     * path="/api/manager/organizations/get-users-by-role/{role}",
     * operationId="ManagerGetOrganizationUsersByRole",
     * tags={"Manager/Organization"},
     * summary="Get a list of organization users by role",
     * description="Retrieve a list of organization users by role.",
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
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string")
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
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     * )
     */
    public function getOrganizationUsersListByRole(Organization $organization, string $role): JsonResponse
    {
        $this->authorize('view', $organization);

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
        } catch (\Exception|NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
