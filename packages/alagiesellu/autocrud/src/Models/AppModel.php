<?php

namespace Alagiesellu\Autocrud\Models;

use Illuminate\Database\Eloquent\Model;

abstract class AppModel extends Model implements ModelInterface
{
    /**
     * By default, all records can be deleted.
     *
     * This function should be overwritten if checks need to be made.
    */
    public function canDelete(): bool
    {
        return true;
    }
}
