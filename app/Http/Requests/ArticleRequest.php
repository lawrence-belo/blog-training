<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules for article submission
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'    => 'required|max:255',
            'category' => 'required',
            'slug'     => 'required|alpha_dash',
            'contents' => 'required'
        ];
    }
}
