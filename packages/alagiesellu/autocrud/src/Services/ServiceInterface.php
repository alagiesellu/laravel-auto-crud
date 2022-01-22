<?php

namespace Alagiesellu\Autocrud\Services;

interface ServiceInterface
{
    public function getRepository();

    public function all();

    public function paginate();

    public function store(array $data);

    public function update(array $data, int $id);

    public function delete(int $id);

    public function show(int $id);

    public function showBy(string $column, $value);

    public function with(string $function);
}
