<?php

namespace Alagiesellu\Autocrud\Repositories;

use Alagiesellu\Autocrud\Models\CRUDModel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

abstract class CRUDRepository extends CRUDQueryBuilder implements CRUDRepositoryInterface
{
    public function __construct(CRUDModel $builder)
    {
        parent::__construct($builder);
    }

    public function all(array $first_query = null): AnonymousResourceCollection
    {
        $this->addLoadWith(__FUNCTION__);

        return
            $this->getJsonResource()::collection(
                $this->buildQuery($first_query)
                    ->get()
            );
    }

    public function paginate(array $first_query = null): AnonymousResourceCollection
    {
        $this->addLoadWith(__FUNCTION__);

        return
            $this->getJsonResource()::collection(
                $this->buildQuery($first_query)
                    ->paginate($this->getPagination())
            );
    }

    public function store($data): JsonResource
    {
        $this->json_encode_arrays($data);

        return
            new ($this->getJsonResource())(
                $this->getBuilder()->create($data)
            );
    }

    public function update($data, $id): JsonResource
    {
        $this->json_encode_arrays($data);

        return new ($this->getJsonResource())(
            DB::transaction(function () use ($id, $data) { // Start the transaction

                $builder_data = $this->getById($id);

                $this->reasonsNotToUpdateCheck($builder_data->reasonsNotToUpdate());

                $builder_data->update($data);

                return $builder_data;
            })
        ); // End transaction
    }

    public function delete($id): bool
    {
        $this->addLoadWith(__FUNCTION__);

        return DB::transaction(function () use ($id) { // Start the transaction

            $builder_data = $this->getById($id);

            $this->reasonsNotToDeleteCheck($builder_data->reasonsNotToDelete());

            $this->deleteRelations($builder_data->getRelations());

            return $builder_data->delete();

        }); // End transaction
    }

    public function show($id): JsonResource
    {
        $this->addLoadWith(__FUNCTION__);

        return new ($this->getJsonResource())(
            $this->getById($id)
        );
    }

    public function showBy(string $column, $value): JsonResource
    {
        $this->addLoadWith('show');
        return new ($this->getJsonResource())(
            $this->getBy($column, $value)
        );
    }
}
