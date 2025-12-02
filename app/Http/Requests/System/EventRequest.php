<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:events,slug,' . $this->id,
            'video_url' => 'nullable|url',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'thumbnail_image' => 'nullable|image|max:10240',
            'status' => 'boolean',
            'gallery.*' => 'nullable|image|max:10240',

        ];
        return $validation;
    }

}
