<?php

namespace Alagiesellu\Autocrud\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CRUDRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
