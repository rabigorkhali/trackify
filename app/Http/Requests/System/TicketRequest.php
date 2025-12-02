<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ticket_status_id' => 'required|exists:ticket_statuses,id',
            'priority' => 'required|string|in:low,medium,high,critical',
            'type' => 'required|string|in:bug,task,story,epic',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'story_points' => 'nullable|integer|min:0|max:100',
            'status' => 'required|integer|in:0,1',
        ];
    }
}

