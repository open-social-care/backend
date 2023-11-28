<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $users = User::paginate(30);

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(["Error" => $e], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}