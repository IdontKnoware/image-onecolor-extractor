<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ImageStoreRequest extends FormRequest
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
     *
     * Handle error messages for input image
     *
     * @return array
     */
    public function messages()
    {
        return [
            'input_img.required' => 'The image field is required.',
            'input_img.mimes'    => 'The file must be one of those types: <strong>.jpg, .jpeg, .gif, .webp</strong>',
            'input_img.max'      => 'The maximum allowed file size is 2MB'
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
            'input_img' => 'required|image|mimes:jpeg,jpg,gif,webp,putamerda|max:2048'
        ];
    }
}
