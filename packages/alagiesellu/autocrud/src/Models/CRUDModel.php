<?php

namespace Alagiesellu\Autocrud\Models;

use Illuminate\Database\Eloquent\Model;

abstract class CRUDModel extends Model implements CRUDModelInterface
{

    public function reasonsNotToDelete(): array
    {
        return [];
    }

    public function reasonsNotToUpdate(): array
    {
        return [];
    }
}
