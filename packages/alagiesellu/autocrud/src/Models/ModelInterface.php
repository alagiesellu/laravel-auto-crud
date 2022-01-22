<?php

namespace Alagiesellu\Autocrud\Models;

interface ModelInterface
{
    public function canDelete(): bool;
}
