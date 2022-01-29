<?php

namespace Alagiesellu\Autocrud\Models;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Model;

abstract class CRUDModel extends Model implements CRUDModelInterface
{
    protected array
        $searchByColumn,
        $loadWiths = [];

    protected string $orderByColumn;

    protected string $jsonResource;

    public function getJsonResource(): string
    {
        return
            $this->jsonResource ?? JsonResource::class
            ;
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
