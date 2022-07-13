<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class CreateRequest extends FormRequest
{
    use ConvertsBase64ToFiles;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function base64FileKeys(): array
    {
        return [
            'cover' => 'cover.jpg',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'book_category.id' => 'required|exists:m_book_categories,id',
            'title' => 'required|max:100',
            'author' => 'required|max:100',
            'publisher' => 'required|max:100',
            'isbn'  => 'required|max:100',
            'cover' => 'required|file|image',
            'stock' => 'required|numeric',
            'description' => 'required|max:100',
            'status' => 'required|in:available,unavailable',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
}
