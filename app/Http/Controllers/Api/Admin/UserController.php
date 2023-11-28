<?php

namespace App\Http\Controllers\Api\Admin;

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
            return response()->json(["Error" => $e], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();

        dd($data);
    }
}
