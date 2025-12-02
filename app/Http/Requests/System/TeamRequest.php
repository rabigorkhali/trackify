<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
            'contact' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'join_date' => 'nullable|date',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'image' => 'nullable|image|max:20480', // Adjust max size as needed
            'position' => 'nullable|integer',
        ];
        return $validation;
    }
}
