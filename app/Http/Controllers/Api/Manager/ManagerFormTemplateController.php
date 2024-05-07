<?php

namespace App\Http\Controllers\Api\Manager;

use App\Actions\Manager\Organization\OrganizationUpdateAction;
use App\DTO\Manager\FormTemplateDTO;
use App\DTO\Manager\OrganizationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Manager\FormTemplateCreateRequest;
use App\Http\Requests\Api\Manager\OrganizationRequest;
use App\Http\Resources\Api\Manager\OrganizationResource;
use App\Http\Resources\Api\Manager\UserListResource;
use App\Http\Resources\Api\Shared\PaginationResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ManagerFormTemplateController extends Controller
{
    public function store(FormTemplateCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dto = new FormTemplateDTO($data);
        dd($dto);
        FormTemplateCreateAction::execute($dto);
    }
}
