<?php

namespace App\Http\Controllers\Api\Manager;

use App\Actions\Manager\Organization\OrganizationUpdateAction;
use App\DTO\Manager\OrganizationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Manager\OrganizationRequest;
use App\Http\Resources\Api\Manager\OrganizationResource;
use App\Http\Resources\Api\Manager\UserListResource;
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
     * path="/api/manager/organizations-get-info",
     * operationId="ManagerGetOrganization",
     * tags={"Manager/Organization"},
     * summary="Get organization info",
     * description="Retrieve organization info.",
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
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="document_type", type="string"),
     *                 @OA\Property(property="document", type="string")
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
    public function getOrganizationInfo(Organization $organization): JsonResponse
    {
        try {
            return response()->json([
                'organization' => OrganizationResource::make($organization),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/manager/organizations-update",
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
    public function update(OrganizationRequest $request, Organization $organization): JsonResponse
    {
        try {
            $data = $request->validated();

            $dto = new OrganizationDTO($data);
            OrganizationUpdateAction::execute($dto, $organization);

            return response()->json(['message' => __('messages.common.success_update')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
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
     *             @OA\Property(property="message", type="string")
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
                'data' => UserListResource::collection($paginate),
                'pagination' => PaginationResource::make($paginate),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception|NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
