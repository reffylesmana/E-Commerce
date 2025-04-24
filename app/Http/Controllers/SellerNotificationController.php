<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Carbon\Carbon;

class SellerNotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all notifications for seller
        $notifications = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('seller.notifications', compact('notifications'));
    }
    
    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->first();
        
        if ($notification) {
            $notification->update(['read_at' => Carbon::now()]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        DatabaseNotification::where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);
        
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
    
    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $notification = DatabaseNotification::where('id', $id)
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
        
        $notifications = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $unreadCount = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
}