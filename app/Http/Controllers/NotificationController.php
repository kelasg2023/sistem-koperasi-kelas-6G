<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class NotificationController extends Controller
{
    use ApiResponse;

    /**
     * Get all notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $notifications = $user->notifications()->take(50)->get()->map(function ($notif) {
            return [
                'id' => $notif->id,
                'data' => $notif->data,
                'read_at' => $notif->read_at,
                'created_at' => $notif->created_at,
            ];
        });

        $unreadCount = $user->unreadNotifications()->count();

        return $this->successResponse([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ], 'Berhasil mengambil daftar notifikasi');
    }

    /**
     * Mark a specific notification or all notifications as read.
     */
    public function markAsRead(Request $request, $id = null)
    {
        $user = $request->user();

        if ($id) {
            $notification = $user->notifications()->find($id);
            if ($notification) {
                $notification->markAsRead();
                return $this->successResponse(null, 'Notifikasi berhasil ditandai telah dibaca');
            }
            return $this->errorResponse('Notifikasi tidak ditemukan', 404);
        }

        // Mark all as read if no ID provided
        $user->unreadNotifications->markAsRead();
        return $this->successResponse(null, 'Semua notifikasi berhasil ditandai telah dibaca');
    }
}
