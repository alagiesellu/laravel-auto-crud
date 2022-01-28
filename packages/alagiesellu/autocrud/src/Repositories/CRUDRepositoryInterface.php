<?php

namespace Alagiesellu\Autocrud\Repositories;

interface CRUDRepositoryInterface
{
    public function all();

    public function paginate();

    public function store(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function show($id);

    public function showBy(string $column, $value);
}
