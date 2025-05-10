<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(10);
        
        return view('notifications.index', compact('notifications'));
    }

    
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return redirect()->back()->with('success', 'Notification marquée comme lue');
    }

   
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

   
    public function getCount()
    {
        return response()->json([
            'count' => auth()->user() ? auth()->user()->unreadNotifications->count() : 0
        ]);
    }

    
    public function getDropdownContent()
    {
        if (!auth()->user()) {
            return response()->json(['html' => '']);
        }
        
        $notifications = auth()->user()->unreadNotifications->take(5);
        $view = view('layouts.partials.notification-dropdown', compact('notifications'))->render();
        
        return response()->json(['html' => $view]);
    }
}