<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Manager\FormTemplateMultipleChoiceQuestionResource;
use App\Models\FormTemplate;
use App\Models\MultipleChoiceQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ManagerFormTemplateMultipleChoiceQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FormTemplate $formTemplate): JsonResponse
    {
        $this->authorize('view', $formTemplate);

        try {
            $multipleChoiceQuestions = MultipleChoiceQuestion::query()
                ->where('form_template_id', $formTemplate->id)
                ->with('options')
                ->get();

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => FormTemplateMultipleChoiceQuestionResource::collection($multipleChoiceQuestions),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FormTemplate $formTemplate, MultipleChoiceQuestion $multipleChoiceQuestion): JsonResponse
    {
        $this->authorize('view', $formTemplate);

        try {
            $multipleChoiceQuestion->load('multipleChoiceOptions');

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => FormTemplateMultipleChoiceQuestionResource::make($multipleChoiceQuestion),
            ], HttpResponse::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FormTemplate $formTemplate, MultipleChoiceQuestion $multipleChoiceQuestion): JsonResponse
    {
        $this->authorize('update', $formTemplate);

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'answer_required' => 'required|boolean',
            'options' => 'present|array',
            'options.*' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($multipleChoiceQuestion, $validated) {
                $multipleChoiceQuestion->update([
                    'description' => $validated['description'],
                    'answer_required' => $validated['answer_required'],
                ]);

                $multipleChoiceQuestion->multipleChoiceOptions()->delete();

                $optionsData = [];
                foreach ($validated['options'] as $optionText) {
                    $optionsData[] = ['description' => $optionText];
                }

                if (!empty($optionsData)) {
                    $multipleChoiceQuestion->multipleChoiceOptions()->createMany($optionsData);
                }
            });

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_update')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormTemplate $formTemplate, MultipleChoiceQuestion $multipleChoiceQuestion): JsonResponse
    {
        $this->authorize('delete', $formTemplate);
        try {
            $multipleChoiceQuestion->delete();
            return response()->json(['type' => 'success', 'message' => __('messages.common.success_destroy')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}