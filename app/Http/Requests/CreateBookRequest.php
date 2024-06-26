<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'image' => 'required|mimes:png,jpg,svg,bmp',
            'book_file' => 'required|mimes:pdf',
            'book_name' => 'required|max:200',
            // 'description' => 'required',
            'category_id' => 'required',
            'authors' => 'required',
            'release_date' => 'required|date',
            'page_count' => 'required|min:0'
        ];
    }
}
