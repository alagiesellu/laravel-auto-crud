<?php

namespace Alagiesellu\Autocrud\Models;

interface CRUDModelInterface
{
    public function getJsonResource(): string;
    public function getSearchByColumns(): array;
    public function getOrderByColumn(): string;
    public function getLoadWiths(): array;
    public function reasonsNotToDelete(): array;
    public function reasonsNotToUpdate(): array;
}
