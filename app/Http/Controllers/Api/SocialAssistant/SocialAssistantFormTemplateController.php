<?php

namespace App\Http\Controllers\Api\SocialAssistant;

use App\Actions\Manager\FormTemplates\FormTemplateCreateAction;
use App\Actions\Manager\FormTemplates\FormTemplateUpdateAction;
use App\DTO\Manager\FormTemplateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Manager\FormTemplateCreateRequest;
use App\Http\Requests\Api\Manager\FormTemplateUpdateRequest;
use App\Http\Resources\Api\Manager\FormTemplateResource;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Http\Resources\Api\SocialAssistant\FormTemplateWithQuestionsResource;
use App\Models\FormTemplate;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class SocialAssistantFormTemplateController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/social-assistant/form-templates/select/{organization}",
     * operationId="SocialAssistantFormTemplates",
     * tags={"SocialAssistant/FormTemplates"},
     * summary="Get a list of form templates",
     * description="Retrieve a list of form templates.",
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
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                     @OA\Property(
     *                      type="array",
     *
     *                      @OA\Items(
     *                          type="object",
     *
     *                          @OA\Property(property="id", type="string", example="1"),
     *                          @OA\Property(property="name", type="string", example="Default"),
     *                      )
     *                  ),
     *               )),
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
    public function getToSelect(Organization $organization): JsonResponse
    {
        $this->authorize('viewForOrganization', [FormTemplate::class, $organization]);

        try {
            $formTemplates = FormTemplate::query()
                ->whereHas('organizations', function ($query) use ($organization) {
                    return $query->where('organization_id', $organization->id);
                })->get();

            $toSelect = to_select($formTemplates, 'id', 'title');

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => $toSelect,
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     * path="/api/social-assistant/form-templates/{form_template}",
     * operationId="SocialAssistantGetFormTemplate",
     * tags={"SocialAssistant/FormTemplates"},
     * summary="Get form template",
     * description="Retrieve form template show.",
     * security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The form template id",
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
     *                  @OA\Property(property="title", type="string", example="Social Care"),
     *                  @OA\Property(property="description", type="string", example="Defautl"),
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
    public function show(FormTemplate $formTemplate): JsonResponse
    {
        $this->authorize('view', $formTemplate);

        try {
            $formTemplate->load('shortQuestions');

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => FormTemplateWithQuestionsResource::make($formTemplate),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}