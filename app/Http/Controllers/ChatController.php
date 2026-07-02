<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Swap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Get all swaps that the user is involved in (sender or receiver)
        // and have at least one message or are accepted
        $swaps = Swap::with(['sender', 'receiver', 'offeredSkill', 'requestedSkill'])
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->whereIn('status', ['accepted', 'completed'])
            ->latest('updated_at')
            ->get();
            
        return view('chat.index', compact('swaps'));
    }

    public function show($swapId)
    {
        $userId = Auth::id();
        $swap = Swap::with(['sender', 'receiver', 'offeredSkill', 'requestedSkill'])
            ->where('id', $swapId)
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->firstOrFail();

        $messages = Message::where('swap_id', $swapId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark incoming messages as read
        Message::where('swap_id', $swapId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $otherUser = $swap->sender_id === $userId ? $swap->receiver : $swap->sender;

        return view('chat.show', compact('swap', 'messages', 'otherUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'swap_id' => 'required|exists:swaps,id',
            'content' => 'required|string|max:1000',
        ]);

        $userId = Auth::id();
        $swap = Swap::where('id', $request->swap_id)
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->firstOrFail();

        $receiverId = $swap->sender_id === $userId ? $swap->receiver_id : $swap->sender_id;

        $message = Message::create([
            'swap_id' => $request->swap_id,
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'content' => $request->content,
            'type' => 'text',
        ]);

        // Trigger update on swap for ordering in index
        $swap->touch();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
            ]);
        }

        return back();
    }

    public function poll($swapId, Request $request)
    {
        $userId = Auth::id();
        
        // Verify user is part of this swap
        $swap = Swap::where('id', $swapId)
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->firstOrFail();

        $afterId = $request->query('after', 0);
        
        $messages = Message::where('swap_id', $swapId)
            ->where('id', '>', $afterId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark new incoming messages as read
        Message::where('swap_id', $swapId)
            ->where('receiver_id', $userId)
            ->where('id', '>', $afterId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'messages' => $messages->load('sender'),
        ]);
    }
}