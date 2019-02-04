<?php

namespace VGirol\JsonApi\Tests\Tools\Requests;

use VGirol\JsonApi\Requests\JsonApiFormRequest;

class ModelForTestFormRequest extends JsonApiFormRequest
{
    /**
     * For test purpose
     */
    protected function modelNamespace(): string
    {
        return '\\VGirol\\JsonApi\\Tests\\Tools\\Models\\';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'tst_name' => [
                'required',
                'string',
                'max:255',
                'unique:t_test_tst,TST_NAME'
            ],
            'tst_number' => 'integer',
            'tst_creation_date' => 'required|date'
        ];
    }
    public function messages(): array
    {
        return [];
    }
}
