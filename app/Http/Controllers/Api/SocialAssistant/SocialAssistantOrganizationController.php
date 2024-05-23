<?php

namespace App\Http\Controllers\Api\SocialAssistant;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Shared\OrganizationResource;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class SocialAssistantOrganizationController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/social-assistant/organizations",
     * operationId="SocialAssistantGetOrganizations",
     * tags={"SocialAssistant/Organization"},
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
     *                 @OA\Property(property="document", type="string", example="123.456.789-0"),
     *                 @OA\Property(property="phone", type="string", example="(41)3333-3333)"),
     *                 @OA\Property(property="subject_ref", type="string", example="sujeito")
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
    public function index(): JsonResponse
    {
        $this->authorize('viewYours', Organization::class);

        try {
            $currentUser = auth()->user();
            $query = Organization::query()
                ->whereHas('users', function ($query) use ($currentUser) {
                    return $query->where('user_id', $currentUser->id);
                });

            if ($search = request()->get('q', null)) {
                $query->whereRaw("LOWER(name) LIKE '%' || LOWER(?) || '%'", [$search]);
            }

            $paginate = $query->paginate(30);

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => OrganizationResource::collection($paginate),
                'pagination' => PaginationResource::make($paginate),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     * path="/api/social-assistant/organizations/{organization}",
     * operationId="SocialAssistantGetOrganization",
     * tags={"SocialAssistant/Organization"},
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
}
