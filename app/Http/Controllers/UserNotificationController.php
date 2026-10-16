<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\UserNotification;

class UserNotificationController extends Controller
{
    /**
     * Latest notifications + unread count for the logged in frontend user.
     */
    public function list(Request $request)
    {
        $userId = Auth::user()->id;

        $unreadCount = UserNotification::where('receiver_id', $userId)
            ->where('is_read', 0)
            ->count();

        $notifications = UserNotification::where('receiver_id', $userId)
            ->orderByDesc('id')
            ->limit(8)
            ->get(['id', 'type', 'title', 'message', 'is_read', 'created_at'])
            ->map(function ($row) {
                return [
                    'id'      => $row->id,
                    'type'    => $row->type,
                    'title'   => $row->title,
                    'message' => \Str::limit($row->message, 80),
                    'is_read' => $row->is_read,
                    'time'    => $row->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a notification as read and return its full content (shown in a modal).
     */
    public function read($id)
    {
        $notification = UserNotification::where('receiver_id', Auth::user()->id)
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return response()->json(['type' => 'error', 'message' => 'Notification not found.']);
        }

        if ($notification->is_read == 0) {
            $notification->is_read = 1;
            $notification->save();
        }

        return response()->json([
            'type' => 'success',
            'title' => $notification->title,
            'message' => $notification->message,
            'url' => $notification->url,
        ]);
    }

    /**
     * Mark all notifications of the logged in user as read.
     */
    public function readAll()
    {
        UserNotification::where('receiver_id', Auth::user()->id)
            ->where('is_read', 0)
            ->update(['is_read' => 1, 'updated_at' => now()]);

        return response()->json(['type' => 'success', 'message' => 'All notifications marked as read.']);
    }
}
