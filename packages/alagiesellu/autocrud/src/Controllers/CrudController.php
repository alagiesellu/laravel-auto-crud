<?php

namespace Alagiesellu\Autocrud\Controllers;

use Alagiesellu\Autocrud\Requests\CRUDRequest;
use Alagiesellu\Autocrud\Services\CRUDService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class CrudController extends Controller
{
    private CRUDRequest $formRequest;
    private CRUDService $service;

    public function __construct(CRUDService $service, CRUDRequest $formRequest)
    {
        $this->service = $service;
        $this->formRequest = $formRequest;
    }

    /**
     * @throws Exception
     */
    public function all(): AnonymousResourceCollection
    {
        return $this->getService()->all();
    }

    /**
     * @throws Exception
     */
    public function index(): AnonymousResourceCollection
    {
        return $this->getService()->paginate();
    }

    /**
     * @throws Exception
     */
    public function show(int $id): JsonResource
    {
        return $this->getService()->show($id);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        return $this->getService()->delete($id);
    }

    /**
     * @throws Exception
     */
    public function store(Request $request): JsonResource
    {
        $validatedRequest = $this->validateRequest($request);

        return $this->getService()->store($validatedRequest);
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, int $id): JsonResource
    {
        $validatedRequest = $this->validateRequest($request, $id);

        return $this->getService()->update($validatedRequest, $id);
    }

    /**
     * @throws ValidationException
     */
    protected function validateRequest(Request $request, $id = null): array
    {
        $validator = Validator::make(
            $request->all(),
            $this->formRequest->rules($id)
        );

        if ($validator->fails())
            abort(422, $validator->errors());

        return $validator->validated();
    }

    public function getService(): CRUDService
    {
        return $this->service;
    }

    public function apiSuccessResponse(string $message = 'Successful'): JsonResponse
    {
        return response()->json([
            'success' => $message
        ]);
    }

    /**
     * @throws Exception
     */
    public function apiErrorResponse(string $message = 'Oops!!! Something went wrong. Try again.'): JsonResponse
    {
        throw new Exception($message);
    }
}
