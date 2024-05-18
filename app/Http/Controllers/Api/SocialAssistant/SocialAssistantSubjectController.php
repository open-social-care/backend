<?php

namespace App\Http\Controllers\Api\SocialAssistant;

use App\Actions\Shared\AddressCreateAction;
use App\Actions\SocialAssistant\SubjectCreateAction;
use App\Actions\SocialAssistant\SubjectUpdateAction;
use App\DTO\Shared\AddressDTO;
use App\DTO\SocialAssistant\SubjectDTO;
use App\Enums\SkinColorsEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SocialAssistant\SubjectCreateRequest;
use App\Http\Requests\Api\SocialAssistant\SubjectUpdateRequest;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Http\Resources\Api\SocialAssistant\SubjectListResource;
use App\Http\Resources\Api\SocialAssistant\SubjectResource;
use App\Models\City;
use App\Models\Organization;
use App\Models\State;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class SocialAssistantSubjectController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/social-assistant/subjects/{organization}",
     * operationId="SocialAssistantGetSubjects",
     * tags={"SocialAssistant/Subject"},
     * summary="Get a list of subjects",
     * description="Retrieve a list of subjects.",
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
     *                 @OA\Property(property="name", type="string", example="Teste"),
     *                 @OA\Property(property="birth_date", type="string", example="19/05/1990"),
     *                 @OA\Property(property="last_form_answer_date", type="string", example="17/02/2023"),
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
    public function index(Organization $organization): JsonResponse
    {
        $this->authorize('viewByOrganization', [Subject::class, $organization]);

        try {
            $query = Subject::query()
                ->whereHas('organizations', function ($query) use ($organization) {
                    return $query->where('organization_id', $organization->id);
                });

            if ($search = request()->get('q', null)) {
                $query->whereRaw("LOWER(name) LIKE '%' || LOWER(?) || '%'", [$search]);
            }

            $paginate = $query->paginate(30);

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => SubjectListResource::collection($paginate),
                'pagination' => PaginationResource::make($paginate),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/social-assistant/subjects/{organization}",
     *     operationId="SocialAssistantCreateSubject",
     *     tags={"SocialAssistant/Subject"},
     *     summary="Create a new subject",
     *     description="Create a new subject with the provided information in social assistant.",
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
     *         description="User data",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="name", type="string", example="Teste"),
     *             @OA\Property(property="birth_date", type="date", example="1990/05/23"),
     *             @OA\Property(property="nationality", type="string", example="brasil"),
     *             @OA\Property(property="phone", type="string", example="(42) 91234-5789", description="(00) 00000-0000"),
     *             @OA\Property(property="father_name", type="string", example="father example"),
     *             @OA\Property(property="mother_name", type="string", example="mother example"),
     *             @OA\Property(property="cpf", type="string", example="123.456.789-0"),
     *             @OA\Property(property="rg", type="string", example="12.345.678-9"),
     *             @OA\Property(property="skin_color", type="string", example="black", description="black|medium-black|indigenous|white|yellow"),
     *             @OA\Property(property="relative_relation_type", type="string", example="uncle"),
     *             @OA\Property(property="relative_name", type="string", example="uncle name"),
     *             @OA\Property(property="relative_phone", type="string", example="(42) 91234-5789", description="(00) 00000-0000"),
     *             @OA\Property(
     *                  property="addresses",
     *                  type="array",
     *
     *                  @OA\Items(
     *                      type="object",
     *
     *                      @OA\Property(property="street", type="string", example="street name"),
     *                      @OA\Property(property="number", type="string", example="123"),
     *                      @OA\Property(property="district", type="string", example="district example"),
     *                      @OA\Property(property="complement", type="string", example="house"),
     *                      @OA\Property(property="state_id", type="integer", example="1"),
     *                      @OA\Property(property="city_id", type="integer", example="1")
     *                  )
     *              )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User created successfully")
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
     *               @OA\Property(property="name", type="array", description="field with errors",
     *
     *                  @OA\Items(type="string", description="message error", example="Name is required")
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
    public function store(SubjectCreateRequest $request, Organization $organization): JsonResponse
    {
        $this->authorize('createByOrganization', [Subject::class, $organization]);

        try {
            $data = $request->validated();

            $dto = new SubjectDTO($data, $organization, auth()->user());
            $subject = SubjectCreateAction::execute($dto);

            foreach ($data['addresses'] as $address) {
                $dto = new AddressDTO($address, $subject);
                AddressCreateAction::execute($dto);
            }

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_create')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/social-assistant/subjects/{subject}",
     *     operationId="SocialAssistantUpdateSubject",
     *     tags={"SocialAssistant/Subject"},
     *     summary="Update subject",
     *     description="Update subject with the provided information.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The subject id for update",
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
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="name", type="string", example="Teste"),
     *              @OA\Property(property="birth_date", type="date", example="1990/05/23"),
     *              @OA\Property(property="nationality", type="string", example="brasil"),
     *              @OA\Property(property="phone", type="string", example="(42) 91234-5789", description="(00) 00000-0000"),
     *              @OA\Property(property="father_name", type="string", example="father example"),
     *              @OA\Property(property="mother_name", type="string", example="mother example"),
     *              @OA\Property(property="cpf", type="string", example="123.456.789-0"),
     *              @OA\Property(property="rg", type="string", example="12.345.678-9"),
     *              @OA\Property(property="skin_color", type="string", example="black", description="black|medium-black|indigenous|white|yellow"),
     *              @OA\Property(property="relative_relation_type", type="string", example="uncle"),
     *              @OA\Property(property="relative_name", type="string", example="uncle name"),
     *              @OA\Property(property="relative_phone", type="string", example="(42) 91234-5789", description="(00) 00000-0000"),
     *              @OA\Property(
     *                   property="addresses",
     *                   type="array",
     *
     *                   @OA\Items(
     *                       type="object",
     *
     *                       @OA\Property(property="street", type="string", example="street name"),
     *                       @OA\Property(property="number", type="string", example="123"),
     *                       @OA\Property(property="district", type="string", example="district example"),
     *                       @OA\Property(property="complement", type="string", example="house"),
     *                       @OA\Property(property="state_id", type="integer", example="1"),
     *                       @OA\Property(property="city_id", type="integer", example="1")
     *                   )
     *               )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Data updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Data updated successfully")
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
     *               @OA\Property(property="name", type="array", description="field with errors",
     *
     *                  @OA\Items(type="string", description="message error", example="name is required in use")
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
    public function update(SubjectUpdateRequest $request, Subject $subject): JsonResponse
    {
        $this->authorize('update', $subject);

        try {
            $data = $request->validated();

            $dto = new SubjectDTO($data, $subject->organization, auth()->user());
            SubjectUpdateAction::execute($dto, $subject);

            $subject->addresses()->delete();
            foreach ($data['addresses'] as $address) {
                $dto = new AddressDTO($address, $subject);
                AddressCreateAction::execute($dto);
            }

            return response()->json(['type' => 'success', 'message' => __('messages.common.success_update')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/social-assistant/subjects/{subject}",
     *     operationId="SocialAssistantGetSubject",
     *     tags={"SocialAssistant/Subject"},
     *     summary="Get subject infos",
     *     description="Get subject infos",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The subject id for get",
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
     *         @OA\JsonContent(
     *
     *               @OA\Property(property="name", type="string", example="Teste"),
     *               @OA\Property(property="birth_date", type="date", example="1990/05/23"),
     *               @OA\Property(property="nationality", type="string", example="brasil"),
     *               @OA\Property(property="phone", type="string", example="(42) 91234-5789", description="(00) 00000-0000"),
     *               @OA\Property(property="father_name", type="string", example="father example"),
     *               @OA\Property(property="mother_name", type="string", example="mother example"),
     *               @OA\Property(property="cpf", type="string", example="123.456.789-0"),
     *               @OA\Property(property="rg", type="string", example="12.345.678-9"),
     *               @OA\Property(property="skin_color", type="string", example="black", description="black|medium-black|indigenous|white|yellow"),
     *               @OA\Property(property="relative_relation_type", type="string", example="uncle"),
     *               @OA\Property(property="relative_name", type="string", example="uncle name"),
     *               @OA\Property(property="relative_phone", type="string", example="(42) 91234-5789", description="(00) 00000-0000"),
     *               @OA\Property(
     *                    property="addresses",
     *                    type="array",
     *
     *                    @OA\Items(
     *                        type="object",
     *
     *                        @OA\Property(property="street", type="string", example="street name"),
     *                        @OA\Property(property="number", type="string", example="123"),
     *                        @OA\Property(property="district", type="string", example="district example"),
     *                        @OA\Property(property="complement", type="string", example="house"),
     *                        @OA\Property(property="state_id", type="integer", example="1"),
     *                        @OA\Property(property="city_id", type="integer", example="1")
     *                    )
     *                )
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
    public function show(Subject $subject): JsonResponse
    {
        $organization = $subject->organization;
        $this->authorize('viewByOrganization', [Subject::class, $organization]);

        try {
            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => SubjectResource::make($subject),
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/social-assistant/get/form-infos",
     *     operationId="SocialAssistantGetSubjectFormInfos",
     *     tags={"SocialAssistant/Subject"},
     *     summary="Get subject form infos",
     *     description="Get subject form infos",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Get data successfully",
     *
     *         @OA\JsonContent(
     *
     *               @OA\Property(
     *                    property="skinColors",
     *                    type="array",
     *
     *                    @OA\Items(
     *                        type="object",
     *
     *                        @OA\Property(property="id", type="string", example="black"),
     *                        @OA\Property(property="name", type="string", example="Preto(a)"),
     *                    )
     *                ),
     *                @OA\Property(
     *                     property="states",
     *                     type="array",
     *
     *                     @OA\Items(
     *                         type="object",
     *
     *                         @OA\Property(property="id", type="integer", example="1"),
     *                         @OA\Property(property="name", type="string", example="Acre"),
     *                     )
     *                 )
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
    public function getFormInfos(): JsonResponse
    {
        try {
            $skinColors = collect(SkinColorsEnum::cases())
                ->map(function ($skinColor) {
                    return ['id' => $skinColor->value, 'name' => SkinColorsEnum::trans($skinColor->value)];
                });

            $states = to_select(State::all());

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => [
                    'skinColors' => $skinColors,
                    'states' => $states,
                ],
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/social-assistant/get/cities-by-state-to-select/{state}",
     *     operationId="SocialAssistantGetCitiesByStateToSelect",
     *     tags={"SocialAssistant/Subject"},
     *     summary="Get cities by state to select",
     *     description="Get cities by state to select",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Get data successfully",
     *
     *                @OA\Property(
     *                     property="cities",
     *                     type="array",
     *
     *                     @OA\Items(
     *                         type="object",
     *
     *                         @OA\Property(property="id", type="integer", example="1"),
     *                         @OA\Property(property="name", type="string", example="Acrelândia"),
     *                     )
     *                 )
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
    public function getCitiesByStateToSelect(State $state): JsonResponse
    {
        try {
            $cities = City::query()->where('state_id', $state->id)->get();
            $cities = to_select($cities);

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => [
                    'cities' => $cities,
                ],
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
