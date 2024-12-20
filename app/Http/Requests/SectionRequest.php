<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
            "section" => "required|unique:sections,section,except,id",
        ];
    }

    public function messages(): array
    {
        return [
            "section.required" => "نام الماری ضروری می باشد",
            "section.unique" => "این نام قبلا انتخاب شده است",
        ];
    }
}
