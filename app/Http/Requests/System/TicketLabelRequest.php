<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class TicketLabelRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'status' => 'nullable|integer',
        ];
    }
}

