<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        return [
            "firstName" => "required|min:5|max:20",
            "lastName" => "required|min:5|max:20",
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            "password" => "required",
            "phone" => "required",
            "nin" => "required",
            "nic" => "required",
            "original_residence" => "required",
            "current_residence" => "required",
            "fac_id" => "required",
            "dep_id" => "required",
            "status" => "required",
            "type" => "required|in:teacher,student",
        ];
    }
}
