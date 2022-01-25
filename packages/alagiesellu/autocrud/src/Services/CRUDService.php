<?php

namespace Alagiesellu\Autocrud\Services;

use Alagiesellu\Autocrud\Repositories\CRUDRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class CRUDService implements CRUDServiceInterface
{

    private CRUDRepository $repository;

    private string|JsonResource $jsonResource;

    private array $relationships;


    /**
     * FormService constructor.
     * @param CRUDRepository $repository
     * @param string|JsonResource $jsonResource
     * @param array $relationships
     */
    public function __construct(
        CRUDRepository      $repository,
        string|JsonResource $jsonResource = new JsonResource::class,
        array               $relationships = []
    )
    {
        $this->repository = $repository;
        $this->jsonResource = $jsonResource;
        $this->relationships = $relationships;
    }

    public function getRepository(): CRUDRepository
    {
        return $this->repository;
    }

    public function getJsonResource(): string|JsonResource
    {
        return $this->jsonResource;
    }

    /**
     * @throws Exception
     */
    public function all(): AnonymousResourceCollection
    {
        $this->with(__FUNCTION__);

        return $this->getJsonResource()::collection($this->getRepository()->all());
    }

    /**
     * @throws Exception
     */
    public function paginate(): AnonymousResourceCollection
    {
        $this->with(__FUNCTION__);

        return $this->getJsonResource()::collection($this->getRepository()->paginate());
    }

    public function store(array $data): JsonResource
    {
        $this->json_encode_arrays($data);

        return new ($this->getJsonResource())($this->getRepository()->store($data));
    }

    public function update(array $data, int $id): JsonResource
    {
        $this->json_encode_arrays($data);

        return new ($this->getJsonResource())($this->getRepository()->update($data, $id));
    }

    protected function json_encode_arrays(array &$data)
    {
        foreach (array_keys($data) as $key) {
            if (is_array($data[$key]))
                $data[$key] = json_encode($data[$key]);
        }
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        $this->with(__FUNCTION__);

        return $this->getRepository()->delete($id);
    }

    /**
     * @throws Exception
     */
    public function show(int $id): JsonResource
    {
        $this->with(__FUNCTION__);

        return new ($this->getJsonResource())($this->getRepository()->getById($id));
    }

    /**
     * @throws Exception
     */
    public function showBy(string $column, $value): JsonResource
    {
        $this->with('show');

        return new ($this->getJsonResource())($this->getRepository()->findBy($column, $value));
    }

    /**
     * @throws Exception
     */
    public function with(string $function): Builder
    {
        $relationships = [];

        if (array_key_exists($function, $this->relationships))
            $relationships = $this->relationships[$function];

        return $this->getRepository()->with($relationships);
    }
}
