<?php

namespace Starmoozie\MenuPermission\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return starmoozie_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'PUT':
                return [
                    'name'   => 'required|max:20|regex:/^[a-z A-Z]+$/|unique:role,name,'.$this->get('id') ?? request()->route('id'),
                    'access' => $this->jsonValidation()
                ];
                break;

            default:
                return [
                    'name'   => 'required|max:20|regex:/^[a-z A-Z]+$/|unique:role,name',
                    'access' => $this->jsonValidation()
                ];
                break;
        }
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }

    private function jsonValidation()
    {
        return function ($attribute, $value, $fail) {
            $fieldGroups = json_decode($value);

            // do not allow repeatable field to be empty
            if (count($fieldGroups) == 0) {
                return $fail('The access field must have at least one item.');
            }
        };
    }
}
