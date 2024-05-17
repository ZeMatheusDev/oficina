<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'empresa' => 'sometimes|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user_id,
            'phone' => 'max:20',
            'created_at' => 'required',
            'status' => 'required|boolean',
            'group_permissions' => 'sometimes',
            'link_group' => 'sometimes|string',
            'string' => 'boolean',
            'permission' => 'sometimes',
            'profile_picture' => 'nullable|dimensions:max_width=3000,max_height=4000|mimes:jpg,jpeg,png,webp,svg,bmp,gif',
            'password' => 'sometimes|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'O preenchimento desse campo é obrigatório!',
            'max' => 'O tamanho máximo de caracteres suportado por esse campo é de :max',
            'confirmed' => 'Os campos senha e confirmação de senha divergem',
            'email.unique' => 'Esse email já existe!'
        ];
    }
}
