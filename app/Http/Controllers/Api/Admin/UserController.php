<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\User\UserCreateAction;
use App\DTO\Admin\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $search = request()->get('q', null);
            $users = User::search($search)->paginate(30);

            return response()->json($users);
        } catch (\Exception|NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    public function store(UserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $userDto = new UserDTO($data);
            UserCreateAction::execute($userDto);

            return response()->json(['message' => __('messages.common.success_create')], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
