<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all notifications
        $notifications = \Illuminate\Notifications\DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('notifications', compact('notifications'));
    }
    
    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $notification = \Illuminate\Notifications\DatabaseNotification::where('id', $id)
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->first();
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        return redirect()->back();
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
    
    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $notification = \Illuminate\Notifications\DatabaseNotification::where('id', $id)
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->first();
        
        if ($notification) {
            $notification->delete();
            return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
        }
        
        return redirect()->back()->with('error', 'Notifikasi tidak ditemukan.');
    }
    
    /**
     * Get notifications for AJAX requests.
     */
    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = \Illuminate\Notifications\DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $unreadCount = $user->unreadNotifications->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
}
