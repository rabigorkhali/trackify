<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class SmsRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
            'sender' => ['nullable', 'string', 'max:255'],
            'receiver' => ['required', 'array', 'min:1'],
            'receiver.*' => ['required', 'string', 'max:10'], // each phone number
            'message' => ['required', 'string'],
            'status' => ['required', 'in:pending,sent,send-now,failed,inbox,draft'],
            'meta' => ['nullable', 'array'],
        ];
    }
}
