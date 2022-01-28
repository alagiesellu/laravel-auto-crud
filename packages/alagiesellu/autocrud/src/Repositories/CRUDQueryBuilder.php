<?php

namespace Alagiesellu\Autocrud\Repositories;

use Alagiesellu\Autocrud\Models\CRUDModel;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\Pure;

abstract class CRUDQueryBuilder
{
    private string $orderByColumn;
    private array $searchByColumn;
    private mixed $builder;
    private array $loadWiths;

    public function __construct(CRUDModel $builder)
    {
        $this->builder = $builder;
        $this->loadWiths = $builder->getLoadWiths();
        $this->searchByColumn = $builder->getSearchByColumns();
        $this->orderByColumn = $this->loadOrderByColumn($builder->getOrderByColumn());

    }

    #[Pure] public function getJsonResource(): JsonResource
    {
        return $this->builder->getJsonResource();
    }

    private function loadOrderByColumn(string $order_by_column = null)
    {
        return is_null($order_by_column) || ! $this->isOrderByColumnInModel($order_by_column)
            ?
            config('autocrud.query.order_by')
            :
            $order_by_column;
    }

    public function getBuilder()
    {
        return $this->builder;
    }

    public function getById($id): CRUDModel
    {
        return $this->getBuilder()->findOrFail($id);
    }

    protected function getBy($column, $value): CRUDModel
    {
        return $this->getBuilder()->where($column, $value)->firstOrFail();
    }

    protected function json_encode_arrays(array &$data)
    {
        foreach (array_keys($data) as $key) {
            if (is_array($data[$key]))
                $data[$key] = json_encode($data[$key]);
        }
    }
    public function addLoadWith(string $function): Builder
    {
        $relationships = [];

        if (array_key_exists($function, $this->loadWiths))
            $relationships = $this->loadWiths[$function];

        return $this->with($relationships);
    }

    public  function with(array $relationships): Builder
    {
        $this->builder = $this->builder->with($relationships);

        return $this->builder;
    }

    private function addQuery(object $query, string $column, $or = true): object
    {
        if (str_ends_with('_id', $column))
        {
            $equation = '=';

            if (request()->exists($column))
                $q = request()->query($column);
        } else {
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

        return $query;
    }

    public function buildQuery(array $first_query = null): Builder
    {
        $query = $this->buildOrderByQuery();

        if (! is_null($first_query))
            $query->where($first_query);

        if (count($this->searchByColumn))
        {
            $query->where(function($query) {

                $column = array_shift($this->searchByColumn);
                $query = $this->addQuery($query, $column, false);

                foreach ($this->searchByColumn as $column) {
                    $query = $this->addQuery($query, $column);
                }
            });
        }

        return $query;
    }

    protected function getPagination(): int
    {
        $paginate = request()->query('paginate');

        $config_paginate = config('autocrud.query.paginate');

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
                $this->orderByColumn;
    }
    private function getOrderByDirection()
    {
        $request_order_by = request()->query('order_by');

        return
            $this->isValidOrderBy($request_order_by)
                ?
                $request_order_by
                :
                config('autocrud.query.order');
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
    protected function deleteRelations(array $relations): void
    {
        foreach ($relations as $relation)
        {
            $this->reasonsNotToDeleteCheck($relation->reasonsNotToDelete());
            $relation->delete();
        }
    }

    /**
     * @throws Exception
     */
    protected function reasonsNotToDeleteCheck(array $reasons)
    {
        if (count($reasons) !== 0)
            throw new Exception(
                'Cannot Delete Record Because: ' . implode(',', $reasons)
            );

    }

    /**
     * @throws Exception
     */
    protected function reasonsNotToUpdateCheck(array $reasons)
    {
        if (count($reasons) !== 0)
            throw new Exception(
                'Cannot Update Record Because: ' . implode(',', $reasons)
            );

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
