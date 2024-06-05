<?php

namespace App\Http\Controllers\Api\SocialAssistant;

use App\Actions\SocialAssistant\FormAnswer\FormAnswerCreateAction;
use App\Actions\SocialAssistant\FormAnswer\ShortAnswer\ShortAnswerCreateAction;
use App\DTO\SocialAssistant\FormAnswerDTO;
use App\DTO\SocialAssistant\ShortAnswerDTO;
use App\Enums\AuditEventTypesEnum;
use App\Events\AuditCreateEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SocialAssistant\FormAnswerCreateRequest;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Http\Resources\Api\SocialAssistant\FormAnswerListResource;
use App\Http\Resources\Api\SocialAssistant\FormAnswerResource;
use App\Models\FormAnswer;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class SocialAssistantFormAnswersController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/social-assistant/form-answers/{subject}",
     * operationId="SocialAssistantGetFormAnswers",
     * tags={"SocialAssistant/FormAnswers"},
     * summary="Get a list of subjects",
     * description="Retrieve a list of subjects.",
     * security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The subject id",
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
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-05-24T18:19:41.000000Z"),
     *                 @OA\Property(property="user_name", type="string", example="Social Assistant"),
     *                 @OA\Property(property="form_template_title", type="string", example="Default"),
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
    public function index(Subject $subject): JsonResponse
    {
        $this->authorize('viewBySubject', [FormAnswer::class, $subject]);

        try {
            $query = FormAnswer::query()
                ->where('subject_id', $subject->id)
                ->where('user_id', auth()->user()->id)
                ->with(['user', 'formTemplate']);

            $paginate = $query->paginate(30);

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => FormAnswerListResource::collection($paginate),
                'pagination' => PaginationResource::make($paginate),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/social-assistant/form-answers/{subject}",
     *     operationId="SocialAssistantCreateFormAnswers",
     *     tags={"SocialAssistant/FormAnswers"},
     *     summary="Create a new form answers",
     *     description="Create a new form answers with the provided information in social assistant.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The subject id",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data",
     *
     *      @OA\JsonContent(
     *           type="object",
     *
     *           @OA\Property(property="form_template_id", type="integer", example=1),
     *           @OA\Property(property="short_answers", type="array",
     *
     *               @OA\Items(
     *                   type="object",
     *
     *                   @OA\Property(property="short_question_id", type="integer", example=1),
     *                   @OA\Property(property="answer", type="string", nullable=true, example="test answer")
     *               )
     *           )
     *      )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Created successfully")
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
     *               @OA\Property(property="answer", type="array", description="field with errors",
     *
     *                  @OA\Items(type="string", description="message error", example="answer is required")
     *            )
     *          ),
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
    public function store(FormAnswerCreateRequest $request, Subject $subject): JsonResponse
    {
        $this->authorize('createForSubject', [FormAnswer::class, $subject]);

        try {
            DB::beginTransaction();

            $data = $request->validated();

            $dto = new FormAnswerDTO($data, $subject, auth()->user());
            $formAnswer = FormAnswerCreateAction::execute($dto);

            $shortAnswers = data_get($data, 'short_answers', []);
            foreach ($shortAnswers as $shortAnswer) {
                $dto = new ShortAnswerDTO($shortAnswer, $formAnswer, $subject);
                ShortAnswerCreateAction::execute($dto);
            }

            AuditCreateEvent::dispatch($formAnswer, auth()->user(), AuditEventTypesEnum::CREATE, request()->ip());

            DB::commit();

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_create')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/social-assistant/form-answers/{form_answer}",
     *     operationId="SocialAssistantGetFormAnswersShow",
     *     tags={"SocialAssistant/FormAnswers"},
     *     summary="Get form answer infos",
     *     description="Get form answer infos",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The form answer id for get",
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
     *     @OA\JsonContent(
     *          type="object",
     *
     *          @OA\Property(property="type", type="string", example="success"),
     *          @OA\Property(property="message", type="string", example="Successful response."),
     *          @OA\Property(property="data", type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="created_at", type="string", format="date-time", example="2024-05-24T18:19:41.000000Z"),
     *              @OA\Property(property="user_name", type="string", example="Social Assistant"),
     *              @OA\Property(property="form_template_title", type="string", example="Default"),
     *              @OA\Property(property="short_answers", type="array",
     *
     *                  @OA\Items(
     *                      type="object",
     *
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="short_question_id", type="integer", example=1),
     *                      @OA\Property(property="answer", type="string", nullable=true, example="19"),
     *                      @OA\Property(property="short_question", type="object",
     *                          @OA\Property(property="id", type="integer", example=1),
     *                          @OA\Property(property="description", type="string", example="Age?"),
     *                          @OA\Property(property="answer_required", type="boolean", example=false)
     *                      )
     *                  )
     *              )
     *          )
     *      )
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
    public function show(FormAnswer $formAnswer): JsonResponse
    {
        $this->authorize('view', $formAnswer);

        try {
            $formAnswer->load(['shortAnswers', 'shortAnswers.shortQuestion']);

            AuditCreateEvent::dispatch($formAnswer, auth()->user(), AuditEventTypesEnum::VIEW, request()->ip());

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => FormAnswerResource::make($formAnswer),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/social-assistant/form-answers/{form_answers}",
     *     operationId="SocialAssistantDestroyFormAnswers",
     *     tags={"SocialAssistant/FormAnswers"},
     *     summary="Destroy form answer",
     *     description="Destroy form answer with the provided id.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The form answer id",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="destroy successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="destroy successfully")
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
    public function destroy(FormAnswer $formAnswer): JsonResponse
    {
        $this->authorize('delete', $formAnswer);

        try {
            AuditCreateEvent::dispatch($formAnswer, auth()->user(), AuditEventTypesEnum::DELETE, request()->ip());

            $formAnswer->delete();

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_destroy')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
