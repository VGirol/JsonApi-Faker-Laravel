<?php

namespace VGirol\JsonApi\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use VGirol\JsonApi\Exceptions\JsonApiValidationException;
use VGirol\JsonApi\Tools\ClassNameTools;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

abstract class JsonApiFormRequest extends FormRequest
{
    // use ClassNameTools;

    private $cachedRules;

    /**
     * Override \Illuminate\Foundation\Http\FormRequest::createDefaultValidator
     */
    protected function createDefaultValidator(ValidationFactory $factory)
    {
        return $factory->make(
            $this->validationData(),
            $this->container->call([$this, 'preparedRules']),
            $this->preparedMessages(),
            $this->attributes()
        );
    }

    /**
     * Override \Illuminate\Foundation\Http\FormRequest::failedValidation
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new JsonApiValidationException($validator))
                    ->errorBag($this->errorBag);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public abstract function rules(): array;

    /**
     * Get the prepared validation rules that apply to the request.
     *
     * @return array
     */
    public function preparedRules(): array
    {
        if (is_null($this->cachedRules)) {
            $this->cachedRules = array_merge($this->getDefaultRules(), $this->getCustomRules());
        }

        return $this->cachedRules;
    }

    private function getDefaultRules(): array
    {
        $idRule = [
            'string'
        ];
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            array_push($idRule, 'required');
            array_push($idRule, Rule::in([$this->id]));
        }

        $rules = [
            'data.type' => [
                'required',
                'string',
                Rule::in([$this->getObjectResourceType()]),
            ],
            'data.id' => $idRule
        ];

        return $rules;
    }

    private function getCustomRules():  array
    {
        $rules = [];
        foreach ($this->rules() as $customKey => $customRules) {
            if (!is_array($customRules)) {
                $customRules = explode('|', $customRules);
            }
            if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
                $cRules = [];
                foreach ($customRules as $customRule) {
                    // No required
                    if ($customRule == 'required') {
                        continue;
                    }

                    // Unique
                    if (strpos($customRule, 'unique') !== FALSE) {
                        $customRule = str_replace('unique:', NULL, $customRule);
                        $a = explode(',', $customRule);
                        // $a[0] = table, $a[1] = field
                        $customRule = Rule::unique($a[0], $a[1]);
                    }
                    if ($customRule instanceof Unique) {
                        $customRule->ignore($this->id, $this->getModelKeyName());
                    }
                    $cRules[] = $customRule;
                }
            } else {
                $cRules = $customRules;
            }
            $rules['data.attributes.'.$customKey] = $cRules;
        }

        return $rules;
    }

    public function preparedMessages(): array
    {
        $mes = [];

        foreach ($this->preparedRules() as $key => $rules) {
            // if (!is_array(($rules))) {
            //     $rules = explode('|', $rules);
            // }

            foreach ($rules as $rule) {
                if (($rule instanceof Unique) || (strpos($rule, 'unique') !== FALSE)) {
                    $mes[$key . '.unique'] = '(409) ' . trans('validation.unique');
                }
            }
        }
        $mes['data.type.in'] = '(409) ' . trans('validation.in');

        return array_merge($mes, $this->messages());
    }

    protected function getObjectResourceType(): string
    {
        return $this->getModelInstance()->getResourceType();
    }

    protected function getModelKeyName(): string
    {
        return $this->getModelInstance()->getKeyName();
    }

    private function getModelInstance()
    {
        $className = $this->getModelClassName();

        return new $className();
    }

    protected abstract function getModelClassName(): string;
}
