<?php

namespace Alagiesellu\Autocrud\Models;

use Illuminate\Http\Resources\Json\JsonResource;

interface CRUDModelInterface
{
    public function getJsonResource(): JsonResource;
    public function getSearchByColumns(): array;
    public function getOrderByColumn(): string;
    public function getLoadWiths(): array;
    public function reasonsNotToDelete(): array;
    public function reasonsNotToUpdate(): array;
}
