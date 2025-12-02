<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class RedirectionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules =  [
            'title' => 'required|string|max:255',
            'source_link' => 'required|url|unique:redirections,source_link,' . $this->request->get('id'),
            'destination_link' => 'required|url',
            'redirection_type' => 'required',
            'status' => 'nullable|boolean',
        ];
        return $rules;
    }
}
