<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
        $projectId = $this->route('project') ?? $this->id ?? null;
        return [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:10|unique:projects,key,' . $projectId,
            'description' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'status' => 'required|integer|in:0,1',
            'members' => 'nullable|array',
            'members.*' => 'nullable|string|in:admin,member,viewer',
        ];
    }
}

