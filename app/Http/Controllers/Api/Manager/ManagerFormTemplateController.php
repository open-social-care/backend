<?php

namespace App\Http\Controllers\Api\Manager;

use App\Actions\Manager\FormTemplates\FormTemplateCreateAction;
use App\Actions\Manager\FormTemplates\FormTemplateUpdateAction;
use App\DTO\Manager\FormTemplateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Manager\FormTemplateCreateRequest;
use App\Http\Requests\Api\Manager\FormTemplateUpdateRequest;
use App\Http\Resources\Api\Manager\FormTemplateResource;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Models\FormTemplate;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ManagerFormTemplateController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/manager/form-templates/{organization}",
     * operationId="ManagerFormTemplates",
     * tags={"Manager/FormTemplates"},
     * summary="Get a list of form templates",
     * description="Retrieve a list of form templates.",
     * security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="organization",
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
     *                 @OA\Property(property="title", type="string", example="Social Care"),
     *                 @OA\Property(property="description", type="string", example="Default"),
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
    public function index(Organization $organization): JsonResponse
    {
        $this->authorize('viewForOrganization', [FormTemplate::class, $organization]);

        try {
            $query = FormTemplate::query()
                ->whereHas('organizations', function ($query) use ($organization) {
                    return $query->where('organization_id', $organization->id);
                });

            if ($search = request()->get('q', null)) {
                $query->whereRaw("LOWER(title) LIKE '%' || LOWER(?) || '%'", [$search]);
            }

            $paginate = $query->paginate(30);

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => FormTemplateResource::collection($paginate),
                'pagination' => PaginationResource::make($paginate),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/manager/form-templates/{organization}",
     *     operationId="ManagerCreateFormTemplates",
     *     tags={"Manager/FormTemplates"},
     *     summary="Create a new form template",
     *     description="Create a new form template with the provided information.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *          name="organization",
     *          in="path",
     *          description="The organization id",
     *          required=true,
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="form template data",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="title", type="string", example="Social Care"),
     *             @OA\Property(property="description", type="string", example="Default"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="form template created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="form template created successfully")
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
     *                @OA\Property(property="title", type="array", description="field with errors",
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
    public function store(FormTemplateCreateRequest $request, Organization $organization): JsonResponse
    {
        $this->authorize('createForOrganization', [FormTemplate::class, $organization]);

        try {
            $data = $request->validated();

            $dto = new FormTemplateDTO($data);
            FormTemplateCreateAction::execute($dto, $organization);

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_create')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/manager/form-templates/{form_template}",
     *     operationId="ManagerUpdateFormTemplates",
     *     tags={"Manager/FormTemplates"},
     *     summary="Update Form Template",
     *     description="Update Form template with the provided information.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="form_template",
     *         in="path",
     *         description="The form Template id for update",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Form Template data",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="title", type="string", example="Social Care"),
     *             @OA\Property(property="description", type="string", example="Default"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Form Template updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Form Template updated successfully")
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
     *                @OA\Property(property="title", type="array", description="field with errors",
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
    public function update(FormTemplateUpdateRequest $request, FormTemplate $formTemplate): JsonResponse
    {
        $this->authorize('update', $formTemplate);

        try {
            $data = $request->validated();

            $dto = new FormTemplateDTO($data);
            FormTemplateUpdateAction::execute($dto, $formTemplate);

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_update')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/manager/form-templates/{form_template}",
     *     operationId="ManagerDestroyFormTemplates",
     *     tags={"Manager/FormTemplates"},
     *     summary="Destroy form template",
     *     description="Destroy form template with the provided id.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="form_template",
     *         in="path",
     *         description="The form template id for destroy",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="form template destroy successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="form template destroy successfully")
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
    public function destroy(FormTemplate $formTemplate): JsonResponse
    {
        $this->authorize('delete', $formTemplate);

        try {
            $formTemplate->delete();

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_destroy')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     * path="/api/manager/form-templates/{form_template}",
     * operationId="ManagerGetFormTemplate",
     * tags={"Manager/FormTemplates"},
     * summary="Get form template",
     * description="Retrieve form template show.",
     * security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="form_template",
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
            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => FormTemplateResource::make($formTemplate),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
