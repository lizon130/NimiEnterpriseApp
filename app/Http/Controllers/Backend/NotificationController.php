<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\UserNotification;

class NotificationController extends Controller
{
    /**
     * Latest notifications + unread count for the logged in backend user.
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
            ->get(['id', 'type', 'title', 'message', 'url', 'is_read', 'created_at'])
            ->map(function ($row) {
                return [
                    'id'       => $row->id,
                    'type'     => $row->type,
                    'title'    => $row->title,
                    'message'  => \Str::limit($row->message, 90),
                    'url'      => $row->url,
                    'is_read'  => $row->is_read,
                    'time'     => $row->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a single notification as read and return its target url.
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
            'url'  => $notification->url,
            'full_message' => $notification->message,
            'title' => $notification->title,
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
