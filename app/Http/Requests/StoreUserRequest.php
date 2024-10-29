<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            "email" => "required|email|unique:users",
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
            "image" => "required|image|max:1024|min:256"
        ];
    }
}
