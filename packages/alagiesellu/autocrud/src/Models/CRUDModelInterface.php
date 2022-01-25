<?php

namespace Alagiesellu\Autocrud\Models;

interface CRUDModelInterface
{
    public function reasonsNotToDelete(): array;
    public function reasonsNotToUpdate(): array;
}
