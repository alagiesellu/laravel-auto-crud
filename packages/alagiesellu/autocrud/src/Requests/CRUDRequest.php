<?php

namespace Alagiesellu\Autocrud\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CRUDRequest extends FormRequest
{
    private array $rules;
    private bool $authorize;

    public function __construct(array $rules = [], bool $authorize = true)
    {
        $this->rules = $rules;
        $this->authorize = $authorize;

        parent::__construct([], [], [], [], [], [], []);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->authorize;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return $this->rules;
    }
}
