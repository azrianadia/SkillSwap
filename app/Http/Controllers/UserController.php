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
        
        // Get the most recent swap between current user and profile user
        $latestSwap = Swap::where(function ($query) use ($id) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('sender_id', $id)
                  ->where('receiver_id', Auth::id());
        })->latest('created_at')->first();
        
        // Check if there's an accepted/completed swap
        $existingSwap = $latestSwap && in_array($latestSwap->status, ['accepted', 'completed']) 
            ? $latestSwap 
            : null;
        
        // Check if there's a pending swap (only if no accepted/completed swap exists)
        $pendingSwap = $latestSwap && $latestSwap->status === 'pending' 
            ? $latestSwap 
            : null;

        return view('users.show', compact('user', 'unreadNotifications', 'existingSwap', 'pendingSwap'));
    }
}
