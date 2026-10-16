<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class UserNotification extends Model
{
    protected $table = 'user_notification';

    protected $fillable = ['receiver_id', 'type', 'title', 'message', 'url', 'is_read'];

    /**
     * Create notifications for a list of user ids (bulk insert).
     */
    public static function sendToUsers(array $userIds, string $type, string $title, string $message, ?string $url = null): void
    {
        $userIds = array_unique(array_filter($userIds));
        if (empty($userIds)) {
            return;
        }

        $now = now();
        $rows = [];
        foreach ($userIds as $userId) {
            $rows[] = [
                'receiver_id' => $userId,
                'type'        => $type,
                'title'       => $title,
                'message'     => $message,
                'url'         => $url,
                'is_read'     => 0,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        try {
            self::insert($rows);
        } catch (\Exception $e) {
            // Never break the main flow (order/notice save) because of a notification failure.
        }
    }

    /**
     * Notify all backend users whose role has the order.view permission about a new order.
     */
    public static function notifyNewOrder($order): void
    {
        // Order notifications are for admins only
        $userIds = User::where('role', 1)
            ->where('status', 1)
            ->pluck('id')
            ->toArray();

        $ref = $order->invoice_no ?: $order->id;

        self::sendToUsers(
            $userIds,
            'order',
            'New Order Received',
            'A new order (' . $ref . ') has been placed. Please check the order list.',
            '/admin/order'
        );
    }

    /**
     * Notify all non-admin frontend users about a notice.
     */
    public static function notifyNotice($notice): void
    {
        $userIds = User::where('role', '!=', 1)
            ->where('status', 1)
            ->pluck('id')
            ->toArray();

        self::sendToUsers(
            $userIds,
            'notice',
            $notice->title,
            \Str::limit(strip_tags($notice->description), 150),
            '/'
        );
    }
}
