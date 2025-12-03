<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->with(['ticket', 'project', 'triggeredBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('backend.system.notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications for dropdown
     */
    public function getRecent()
    {
        try {
            $notifications = Notification::where('user_id', auth()->id())
                ->with(['ticket.project', 'triggeredBy'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $unreadCount = Notification::where('user_id', auth()->id())
                ->unread()
                ->count();

            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading notifications: ' . $e->getMessage());
            return response()->json([
                'notifications' => [],
                'unread_count' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->unread()
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Clear all read notifications
     */
    public function clearRead()
    {
        Notification::where('user_id', auth()->id())
            ->read()
            ->delete();

        return response()->json(['success' => true]);
    }
}

