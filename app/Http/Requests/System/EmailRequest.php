<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class EmailRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_email' => 'required|email',
            'to_email' => ['required', 'array'],
            'to_email.*' => ['required', 'email'],
            'subject' => 'nullable|string|max:255',
            'body' => 'nullable|max:20000',
            'status' => 'required|in:draft,sent,inbox,send-now,failed'
        ];
    }
}
