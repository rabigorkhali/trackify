<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\TicketChecklist;
use Illuminate\Http\Request;

class TicketChecklistController extends Controller
{
    /**
     * Store a new checklist item
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ticket_id' => 'required|exists:tickets,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $checklist = TicketChecklist::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Checklist item created successfully',
                'checklist' => $checklist
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Update checklist item
     */
    public function update(Request $request, TicketChecklist $checklist)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'is_completed' => 'sometimes|boolean',
            ]);

            if (isset($validated['is_completed'])) {
                $validated['completed_by'] = $validated['is_completed'] ? auth()->id() : null;
                $validated['completed_at'] = $validated['is_completed'] ? now() : null;
            }

            $checklist->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Checklist item updated successfully',
                'checklist' => $checklist->fresh()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Delete checklist item
     */
    public function destroy(TicketChecklist $checklist)
    {
        try {
            $checklist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Checklist item deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }
}

