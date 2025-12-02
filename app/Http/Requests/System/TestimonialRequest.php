<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validation = [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
//            'date' => 'required|date',
            'message' => 'required|string|max:500',
            'status' => 'required|boolean',
            'image' => 'nullable|image|max:10240',
            'position' => 'nullable|integer|max:100',
        ];
        return $validation;
    }
}
