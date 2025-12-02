<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\TicketWatcher;
use Illuminate\Http\Request;

class TicketWatcherController extends Controller
{
    /**
     * Add a watcher to a ticket
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ticket_id' => 'required|exists:tickets,id',
                'user_id' => 'required|exists:users,id',
            ]);

            $watcher = TicketWatcher::firstOrCreate($validated);

            return response()->json([
                'success' => true,
                'message' => 'Watcher added successfully',
                'watcher' => $watcher->load('user')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Remove a watcher from a ticket
     */
    public function destroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'ticket_id' => 'required|exists:tickets,id',
                'user_id' => 'required|exists:users,id',
            ]);

            TicketWatcher::where('ticket_id', $validated['ticket_id'])
                ->where('user_id', $validated['user_id'])
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Watcher removed successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }
}

