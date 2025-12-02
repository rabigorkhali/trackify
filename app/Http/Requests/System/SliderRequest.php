<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SliderRequest extends FormRequest
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
    public function rules(Request $request)
    {
            $validate = [
                'title' => 'nullable|string',
                'sub_title' => 'nullable|string',
                'thumbnail_image' => 'nullable|image|max:2024',
                'timer' => 'nullable|numeric',
                'button1_label' => 'nullable|string',
                'button1_link' => 'nullable|string',
                'button1_color' => 'nullable|string',
                'button1_icon' => 'nullable|string',
                'button2_label' => 'nullable|string',
                'button2_link' => 'nullable|string',
                'button2_color' => 'nullable|string',
                'button2_icon' => 'nullable|string',
                'status' => 'nullable|boolean',
                'short_description' => 'nullable',
                'long_description' => 'nullable',
            ];

        return $validate;
    }

    public function messages()
    {
        return [];
    }
}
