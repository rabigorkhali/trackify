<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\TimeLog;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    /**
     * Store a new time log
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ticket_id' => 'required|exists:tickets,id',
                'time_spent' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'logged_date' => 'required|date',
            ]);

            $validated['user_id'] = auth()->id();

            $timeLog = TimeLog::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Time logged successfully',
                'timeLog' => $timeLog->load('user')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Update a time log
     */
    public function update(Request $request, TimeLog $timeLog)
    {
        try {
            // Only allow user who created the log to update it
            if ($timeLog->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $validated = $request->validate([
                'time_spent' => 'sometimes|required|integer|min:1',
                'description' => 'nullable|string',
                'logged_date' => 'sometimes|required|date',
            ]);

            $timeLog->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Time log updated successfully',
                'timeLog' => $timeLog->fresh()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Delete a time log
     */
    public function destroy(TimeLog $timeLog)
    {
        try {
            // Only allow user who created the log to delete it
            if ($timeLog->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $timeLog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Time log deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }
}

