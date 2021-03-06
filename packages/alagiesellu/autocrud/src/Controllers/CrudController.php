<?php

namespace Alagiesellu\Autocrud\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Alagiesellu\Autocrud\Repositories\CRUDRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Alagiesellu\Autocrud\Requests\CRUDRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

abstract class CrudController extends Controller
{
    private CRUDRequest $formRequest;
    private CRUDRepository $repository;

    public function __construct(CRUDRepository $repository, CRUDRequest $formRequest)
    {
        $this->repository = $repository;
        $this->formRequest = $formRequest;
    }

    /**
     * @throws Exception
     */
    public function all(): AnonymousResourceCollection
    {
        return $this->getRepository()->all();
    }

    /**
     * @throws Exception
     */
    public function index(): AnonymousResourceCollection
    {
        return $this->getRepository()->paginate();
    }

    /**
     * @throws Exception
     */
    public function show(int $id): JsonResource
    {
        return $this->getRepository()->show($id);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        return $this->getRepository()->delete($id);
    }

    /**
     * @throws Exception
     */
    public function store(Request $request): JsonResource
    {
        $validatedRequest = $this->validateRequest($request);

        return $this->getRepository()->store($validatedRequest);
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, int $id): JsonResource
    {
        $validatedRequest = $this->validateRequest($request);

        return $this->getRepository()->update($validatedRequest, $id);
    }

    /**
     * @throws ValidationException
     */
    protected function validateRequest(Request $request): array
    {
        $validator = Validator::make(
            $request->all(),
            $this->formRequest->rules()
        );

        if ($validator->fails())
            abort(422, $validator->errors());

        return $validator->validated();
    }

    public function getRepository(): CRUDRepository
    {
        return $this->repository;
    }

    /**
     * @throws Exception
     */
    public function apiErrorResponse(string $message = 'Oops!!! Something went wrong. Try again.'): JsonResponse
    {
        throw new Exception($message);
    }
}
