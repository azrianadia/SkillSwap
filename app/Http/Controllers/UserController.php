<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Swap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::with(['offeredSkills', 'soughtSkills', 'reviewsReceived'])->findOrFail($id);
        $unreadNotifications = auth()->user()->unreadNotifications->count();
        
        // Check if there's an accepted/completed swap between current user and profile user
        $existingSwap = Swap::where(function ($query) use ($id) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('sender_id', $id)
                  ->where('receiver_id', Auth::id());
        })->whereIn('status', ['accepted', 'completed'])->first();
        
        // Check if there's a pending swap
        $pendingSwap = Swap::where(function ($query) use ($id) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('sender_id', $id)
                  ->where('receiver_id', Auth::id());
        })->where('status', 'pending')->first();

        return view('users.show', compact('user', 'unreadNotifications', 'existingSwap', 'pendingSwap'));
    }
}
