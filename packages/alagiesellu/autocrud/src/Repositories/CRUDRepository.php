<?php

namespace Alagiesellu\Autocrud\Repositories;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class CRUDRepository implements RepositoryInterface
{
    private $builder, $filter_columns, $order_by_column;

    public function __construct($builder, array $filter_columns = [], string $order_by_column = null)
    {
        $this->builder = resolve($builder);
        $this->filter_columns = $filter_columns;

        $this->order_by_column =
            is_null($order_by_column) || ! $this->isOrderByColumnInModel($order_by_column)
                ?
                config('data.query.order_by')
                :
                $order_by_column;
    }

    public function getFilterColumns(): array
    {
        return $this->filter_columns;
    }

    public function getBuilder()
    {
        return $this->builder;
    }

    public function all(array $first_query = null)
    {
        return
            $this->buildQuery($first_query)
                ->get();
    }

    public function paginate(array $first_query = null): LengthAwarePaginator
    {
        return
            $this->buildQuery($first_query)
                ->paginate($this->getPagination());
    }

    public function store($data): Model
    {
        return
            $this->getBuilder()->create($data);
    }

    public function update($data, $id)
    {
        return DB::transaction(function () use ($id, $data) { // Start the transaction

            $builder_data = $this->getById($id);

            $builder_data->update($data);

            return $builder_data;
        }); // End transaction
    }

    public function delete($id): bool
    {
        return DB::transaction(function () use ($id) { // Start the transaction

            $builder_data = $this->getById($id);

            if ($builder_data->canDelete())
            {
                $this->deleteRelations($builder_data->getRelations());

                return $builder_data->delete();
            }

            return false;

        }); // End transaction
    }

    public function getById($id, $with = null)
    {
        if (!is_null($with))
            $this->with($with);

        return $this->getBuilder()->findOrFail($id);
    }

    public function findBy($column, $value)
    {
        return $this->getBuilder()->where($column, $value)->firstOrFail();
    }

    // Eager load database relationships
    public  function with($relations): Builder
    {
        $this->builder = $this->builder->with($relations);

        return $this->builder;
    }

    protected function addQuery(object &$query, string $column, $or = true)
    {
        if (str_ends_with('_id', $column))
        {
            $equation = '=';

            if (request()->exists($column))
                $q = request()->query($column);
        }
        else
        {
            $equation = 'LIKE';

            if (request()->exists('q'))
                $q = '%'.request()->query('q').'%';
        }

        if (isset($q))
        {
            if ($or)
                $query->orWhere(
                    $column,
                    $equation,
                    $q
                );
            else
                $query->where(
                    $column,
                    $equation,
                    $q
                );
        }
    }

    public function buildQuery(array $first_query = null): Builder
    {
        $query = $this->buildOrderByQuery();

        if (! is_null($first_query))
            $query->where($first_query);

        if (count($this->filter_columns))
        {
            $query->where(function($query) {

                $column = array_shift($this->filter_columns);
                $this->addQuery($query, $column, false);

                foreach ($this->filter_columns as $column) {
                    $this->addQuery($query, $column);
                }
            });
        }

        return $query;
    }

    protected function getPagination(): int
    {
        $paginate = request()->query('paginate');

        $config_paginate = config('data.query.paginate');

        return
            is_numeric($paginate) && $paginate > 0 && $paginate <= $config_paginate * 4
            ?
            $paginate
            :
            $config_paginate;

    }

    private function isOrderByColumnInModel($order_by_column): bool
    {
        return
            in_array(
                $order_by_column,
                array_merge(
                    $this->getBuilder()->getModel()->getFillable(),
                    ['created_at', 'updated_at']
                ),
                true
            );
    }
    private function isValidOrderBy($order_by): bool
    {
        return
            in_array(
                $order_by,
                ['desc', 'asc'],
                true
            );
    }
    private function getOrderByColumn()
    {
        $order_by_column_request = request()->query('order_by_column');

        return
            $this->isOrderByColumnInModel(
                $order_by_column_request
            )
                ?
                $order_by_column_request
                :
                $this->order_by_column;
    }
    private function getOrderByDirection()
    {
        $request_order_by = request()->query('order_by');

        return
            $this->isValidOrderBy($request_order_by)
                ?
                $request_order_by
                :
                config('data.query.order');
    }
    private function buildOrderByQuery(): Builder
    {
        return
            $this->getBuilder()
                ->orderBy(
                    $this->getOrderByColumn(),
                    $this->getOrderByDirection()
                );
    }

    /**
     * @throws Exception
     */
    private function deleteRelations(array $relations): void
    {
        foreach ($relations as $relation)
        {
            if (!$relation->canDelete())
                throw new Exception('Relationship deletion fail.');

            $relation->delete();
        }
    }

    public function where(array $conditions, string $orderColumn = null, string $orderDirection = null)
    {
        if (is_null($orderColumn))
            $orderColumn = $this->getOrderByColumn();

        if (is_null($orderDirection))
            $orderDirection = $this->getOrderByDirection();

        return $this->getBuilder()->orderBy($orderColumn, $orderDirection)->where($conditions)->get();
    }
}
