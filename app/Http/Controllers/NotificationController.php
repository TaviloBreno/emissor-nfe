<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $unreadCount = Auth::user()->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function getUnread()
    {
        $notifications = Auth::user()
            ->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'notifications' => $notifications->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->data['title'] ?? 'Notificação',
                    'message' => $notification->data['message'] ?? '',
                    'icon' => $notification->data['icon'] ?? 'fas fa-bell',
                    'color' => $notification->data['color'] ?? 'blue',
                    'url' => $notification->data['url'] ?? null,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'read_at' => $notification->read_at
                ];
            }),
            'count' => Auth::user()->unreadNotifications()->count()
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function markAsUnread($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsUnread();

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }

    public function deleteAll()
    {
        Auth::user()->notifications()->delete();

        return response()->json(['success' => true]);
    }

    public function deleteRead()
    {
        Auth::user()->notifications()->whereNotNull('read_at')->delete();

        return response()->json(['success' => true]);
    }

    // Teste - criar notificação de exemplo
    public function createTest()
    {
        Auth::user()->notify(new \App\Notifications\NotaFiscalAprovada((object)[
            'id' => 1,
            'numero' => '000000001'
        ]));

        return redirect()->back()->with('success', 'Notificação de teste criada!');
    }
}