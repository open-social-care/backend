<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class CityController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/state/{state}/cities",
     * operationId="GetCitiesByStateToSelect",
     * tags={"Shared/State"},
     * summary="Get a list of cities by state",
     * description="Retrieve a list of cities by state.",
     * security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *           name="id",
     *           in="path",
     *           description="The state id for get cities",
     *           required=true,
     *
     *           @OA\Schema(
     *               type="integer"
     *           )
     *       ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                     property="data",
     *                     type="array",
     *
     *                     @OA\Items(
     *                         type="object",
     *
     *                         @OA\Property(property="id", type="integer", example="1"),
     *                         @OA\Property(property="name", type="string", example="Acrelândia"),
     *                     )
     *             )
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
    public function index(State $state): JsonResponse
    {
        try {
            $cities = City::query()->where('state_id', $state->id)->get();
            $cities = to_select($cities);

            return response()->json([
                'type' => 'success',
                'message' => __('messages.common.success_view'),
                'data' => $cities,
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
