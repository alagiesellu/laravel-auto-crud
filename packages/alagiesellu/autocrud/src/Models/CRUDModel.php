<?php

namespace Alagiesellu\Autocrud\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class CRUDModel extends Model implements CRUDModelInterface
{
    protected array
        $searchByColumn,
        $loadWiths = [];

    protected string $orderByColumn;

    protected JsonResource $jsonResource;

    public function getJsonResource(): JsonResource
    {
        return $this->jsonResource;
    }

    public function getSearchByColumns(): array
    {
        return $this->searchByColumn;
    }

    public function getOrderByColumn(): string
    {
        return $this->orderByColumn;
    }

    public function getLoadWiths(): array
    {
        return $this->loadWiths;
    }

    public function reasonsNotToDelete(): array
    {
        return [];
    }

    public function reasonsNotToUpdate(): array
    {
        return [];
    }
}
