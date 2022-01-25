<?php

namespace Alagiesellu\Autocrud\Repositories;

interface CRUDRepositoryInterface
{
    public function all();

    public function paginate();

    public function store(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function getById($id);

    public function findBy($column, $value);
}
