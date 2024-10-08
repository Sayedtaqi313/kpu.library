<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentProfileRequest extends FormRequest
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
            "email" => "required|email|unique:users,email,except,id",
            "nin" => "required",
            "nic" => "required",
            "current_residence" => "required",
            "original_residence" => "required",
            "phone" => "required",
            "fac_id" => "required",
            "dep_id" => "required"
        ];
    }
}
