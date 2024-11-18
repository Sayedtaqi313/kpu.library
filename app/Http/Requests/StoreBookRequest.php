<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            "title" => "required",
            "author" => "required",
            "publisher" => "required",
            "publicationYear" => "required",
            "lang" => "required",
            "edition" => "required",
            "translator" => "required",
            "isbn" => "required",
            "description" => "nullable",
            "code" => "required",
            "cat_id" => "required",
            "dep_id" => "required",
            "sec_id" => "required",
            "format" => "required",
            "borrow" => "required",
            "total" => "required",
            "shelf" => "required",
            "image" => "required|image|mimes:png,jpg,jpeg|max:1024",
            'pdf' => 'nullable|mimes:pdf'
        ];
    }
}
