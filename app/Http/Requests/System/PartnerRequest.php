<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
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
            'name'           => 'required|string|max:255',
            'address'        => 'nullable|string|max:255',
            'description'    => 'nullable|max:10000',
            'contact'        => 'nullable|string|max:20',
            'facebook_url'   => 'nullable|url|max:255',
            'instagram_url'  => 'nullable|url|max:255',
            'youtube_url'    => 'nullable|url|max:255',
            'linkedin_url'   => 'nullable|url|max:255',
            'twitter_url'    => 'nullable|url|max:255',
            'status'         => 'boolean',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'position'       => 'nullable|integer',
        ];
        return $validation;
    }
}
