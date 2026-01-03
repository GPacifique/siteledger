<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsController extends Controller
{
    /**
     * Show notifications dashboard for current user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $scope = $request->query('scope', 'all'); // 'all' | 'unread'

        $query = $user->notifications()->latest();
        if ($scope === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate(20)->withQueryString();

        $counts = [
            'all' => $user->notifications()->count(),
            'unread' => $user->notifications()->whereNull('read_at')->count(),
        ];

        return view('notifications.index', compact('notifications', 'counts', 'scope'));
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(string $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }
        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        $user = Auth::user();
        $user->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    /**
     * Delete a notification.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $notification->delete();
        return redirect()->back()->with('success', 'Notification deleted');
    }
}
