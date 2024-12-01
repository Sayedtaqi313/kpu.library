<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
            "name" => "required|unique:departments,name,except,id",
            "fac_id" => "required|exists:faculties,id"
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "نام ضروری می باشد",
            "name.unique" => "ای نام قبلا انتخاب شده است",
            "fac_id.required" => "فاکلوته معتبر نمی باشد"
        ];
    }
}
