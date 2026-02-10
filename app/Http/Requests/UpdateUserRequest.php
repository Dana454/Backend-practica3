<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       $id = $this->route('id');

        $rules = [
            'name' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],
            'username' => ['string', 'max:255', 'unique:users,username,' . $id],
            'email' => ['email', 'unique:users,email,' . $id],
            'hiring_date' => ['date'],
            'dui' => ['string', 'unique:users,dui,' . $id, 'regex:/^\d{8}-\d$/'],
            'phone_number' => ['string', 'numeric'],
            'birth_date' => ['date', 'before:today'],
        ];

        if ($this->isMethod('put')) {
            $rules['name'][] = 'required';
            $rules['lastname'][] = 'required';
            $rules['username'][] = 'required';
            $rules['email'][] = 'required';
            $rules['dui'][] = 'required';
            $rules['birth_date'][] = 'required';
        }

        return $rules;
    }
    }
}
