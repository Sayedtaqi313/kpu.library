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
    public function messages(): array
    {
        return [
            "title.required" => "عنوان کتاب ضروری می باشد",
            "author.required" => "نویسنده کتاب ضروری می باشد",
            "publisher.required" => "منتشر کننده کتاب ضروری می باشد",
            "publicationYear.required" => "سال انتشار ضروری می باشد",
            "lang.required" => "زبان ضروری می باشد",
            "edition.required" => "سال چاپ ضروری می باشد",
            "isbn.required" => "isbn ضروری می باشد",
            "description.required" => "توضیخات ضروری می باشد",
            "code.required" => "کود کتاب ضروری می باشد",
            "cat_id.required" => "کتگوری ضروری می باشد",
            "dep_id.required" => "دیپارتمنت ضروری می باشد",
            "sec_id.required" => "الماری ضروری می باشد",
            "format.required" => "فرمت کتاب ضروری می باشد",
            "borrow.required" => "فیلد قرض گرفتن ضروری می باشد",
            "total.required" => "مجموع کتاب ضروری می باشد",
            "shelf.required" => "نمبر قفسه الماری ضروری می باشد",
            "image.required" => "عکس کتاب ضروری می باشد",
        ];
    }
}
