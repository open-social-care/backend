<?php

namespace App\Http\Controllers\Api\Manager;

use App\Actions\Manager\FormTemplates\ShortQuestions\ShortQuestionCreateAction;
use App\Actions\Manager\FormTemplates\ShortQuestions\ShortQuestionUpdateAction;
use App\DTO\Manager\ShortQuestionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Manager\FormTemplateShortQuestionCreateRequest;
use App\Http\Resources\Api\Manager\FormTemplateShortQuestionResource;
use App\Models\FormTemplate;
use App\Models\ShortQuestion;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ManagerFormTemplateShortQuestionController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/manager/form-template/{form_template}/short-questions",
     * operationId="ManagerFormTemplatesShortQuestions",
     * tags={"Manager/FormTemplates/ShortQuestions"},
     * summary="Get a list of form template short questions",
     * description="Retrieve a list of short questions.",
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
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Successful response"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="description", type="string", example="Age?"),
     *                 @OA\Property(property="answer_required", type="boolean", example="true"),
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
    public function index(FormTemplate $formTemplate): JsonResponse
    {
        $this->authorize('view', FormTemplate::class);

        try {
            $shortQuestions = ShortQuestion::query()
                ->where('form_template_id', $formTemplate->id)
                ->get();

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => FormTemplateShortQuestionResource::collection($shortQuestions),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/manager/form-template/{form_template}/short-questions",
     *     operationId="ManagerCreateFormTemplateShortQuestions",
     *     tags={"Manager/FormTemplates/ShortQuestions"},
     *     summary="Create a new form template short question",
     *     description="Create a new short question for form template with the provided information.",
     *     security={{"sanctum":{}}},
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
     *     @OA\RequestBody(
     *         required=true,
     *         description="form template short question data",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="description", type="string", example="Age?"),
     *             @OA\Property(property="answer_required", type="boolean", example="true", description="true/false"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="form template short question created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="short question created successfully")
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
     *                @OA\Property(property="description", type="array", description="field with errors",
     *
     *                  @OA\Items(type="string", description="message error", example="The field description is required")
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
    public function store(FormTemplateShortQuestionCreateRequest $request, FormTemplate $formTemplate): JsonResponse
    {
        $this->authorize('create', FormTemplate::class);

        try {
            $data = $request->validated();

            $dto = new ShortQuestionDTO($data);
            ShortQuestionCreateAction::execute($dto, $formTemplate);

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_create')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/manager/form-template/{form_template}/short-questions/{short_question}",
     *     operationId="ManagerUpdateFormTemplatesShortQuestion",
     *     tags={"Manager/FormTemplates/ShortQuestions"},
     *     summary="Update Form Template short questions",
     *     description="Update Form template short question with the provided information.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The form Template id from short question",
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
     *         description="The form Template short question id for update",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Form Template short question data",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="description", type="string", example="Age?"),
     *             @OA\Property(property="answer_required", type="boolean", example="True", description="true/false"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Form Template short question updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Form Template short question updated successfully")
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
     *                @OA\Property(property="description", type="array", description="field with errors",
     *
     *                  @OA\Items(type="string", description="message error", example="The field description is required")
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
    public function update(
        FormTemplateShortQuestionCreateRequest $request,
        FormTemplate $formTemplate,
        ShortQuestion $shortQuestion): JsonResponse
    {
        $this->authorize('update', $formTemplate);

        try {
            $data = $request->validated();

            $dto = new ShortQuestionDTO($data);
            ShortQuestionUpdateAction::execute($dto, $shortQuestion);

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_update')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/manager/form-templates/{form_template}/short-questions/{short_question}",
     *     operationId="ManagerDestroyFormTemplatesShortQuestion",
     *     tags={"Manager/FormTemplates/ShortQuestions"},
     *     summary="Destroy form template short questions",
     *     description="Destroy short questions from form template with the provided id.",
     *     security={{"sanctum":{}}},
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
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The form template short questions id for destroy",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="form template short questions destroy successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="form template short questions destroy successfully")
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
    public function destroy(FormTemplate $formTemplate, ShortQuestion $shortQuestion): JsonResponse
    {
        $this->authorize('delete', $formTemplate);

        try {
            $shortQuestion->delete();

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_destroy')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     * path="/api/manager/form-templates/{form_template}/short-questions/{short_question}",
     * operationId="ManagerGetFormTemplateShortQuestion",
     * tags={"Manager/FormTemplates/ShortQuestions"},
     * summary="Get form template short question" ,
     * description="Retrieve form template short question show.",
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
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The form template short question id for show",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *        )
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
     *                  @OA\Property(property="description", type="string", example="Age?"),
     *                  @OA\Property(property="answer_required", type="boolean", example="true"),
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
    public function show(FormTemplate $formTemplate, ShortQuestion $shortQuestion): JsonResponse
    {
        $this->authorize('view', $formTemplate);

        try {
            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => FormTemplateShortQuestionResource::make($shortQuestion),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
