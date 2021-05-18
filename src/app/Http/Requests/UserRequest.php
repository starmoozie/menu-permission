<?php

namespace Starmoozie\MenuPermission\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
                    'email'    => 'required|email|max:30|unique:users,email,'.$this->get('id') ?? request()->route('id'),
                    'name'     => 'required|regex:/^[a-z A-Z]+$/',
                    'password' => 'confirmed',
                ];
                break;
            
            default:
                return [
                    'email'    => 'required|email|max:30|unique:users,email',
                    'name'     => 'required|regex:/^[a-z A-Z]+$/',
                    'password' => 'required|confirmed',
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
}
