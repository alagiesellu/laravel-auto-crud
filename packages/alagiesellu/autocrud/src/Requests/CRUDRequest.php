<?php

namespace Alagiesellu\Autocrud\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CRUDRequest extends FormRequest
{
    private array $rules;
    private bool $authorize;
    private string $idKey;

    public function __construct(array $rules = [], bool $authorize = true, string $idKey = '{id}')
    {
        $this->rules = $rules;
        $this->authorize = $authorize;
        $this->idKey = $idKey;

        parent::__construct([], [], [], [], [], [], []);
    }

    public function authorize(): bool
    {
        return $this->authorize;
    }

    public function rules($id = null): array
    {
        if (! is_null($id) && $id !== '')
            $this->injectID($id);

        return $this->rules;
    }

    private function injectID($id)
    {
        foreach ($this->rules as &$rule)
        {
            if (is_array($rule))
                $this->rulesInArray($rule, $id);

            if (is_string($rule))
                $this->rulesInString($rule, $id);
        }
    }

    private function rulesInArray(array &$rule, $id)
    {
        foreach ($rule as &$_rule)
            $this->rulesInString($_rule, $id);

    }

    private function rulesInString(string &$rule, $id)
    {
        $rule = str_replace($this->idKey, $id, $rule);
    }
}
