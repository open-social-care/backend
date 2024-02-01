<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\Organization\OrganizationCreateAction;
use App\Actions\Admin\Organization\OrganizationUpdateAction;
use App\DTO\Admin\OrganizationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrganizationRequest;
use App\Http\Resources\Api\Admin\OrganizationListResource;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
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
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="document_type", type="string"),
     *                 @OA\Property(property="document", type="string")
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
            $paginate = Organization::search($search)->paginate(30);

            return response()->json([
                'data' => OrganizationListResource::collection($paginate),
                'pagination' => PaginationResource::make($paginate),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception|NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
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
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="phone", type="string", description="(00) 0000-0000"),
     *             @OA\Property(property="document_type", type="string", description="CNPJ/CPF"),
     *             @OA\Property(property="document", type="string", description="00.000.000/0000-00 / 000.000.000-00"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Organization created successfully",
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
    public function store(OrganizationRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $dto = new OrganizationDTO($data);
            OrganizationCreateAction::execute($dto);

            return response()->json(['message' => __('messages.common.success_create')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
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
     * *             type="object",
     * *
     * *             @OA\Property(property="name", type="string"),
     * *             @OA\Property(property="phone", type="string", description="(00) 0000-0000"),
     * *             @OA\Property(property="document_type", type="string", description="CNPJ/CPF"),
     * *             @OA\Property(property="document", type="string", description="00.000.000/0000-00 / 000.000.000-00"),
     * *         )
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
    public function destroy(Organization $organization): JsonResponse
    {
        try {
            $organization->delete();

            return response()->json(['message' => __('messages.common.success_destroy')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
