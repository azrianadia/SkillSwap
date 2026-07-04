<?php

namespace App\Http\Controllers;

use App\Models\Swap;
use App\Models\User;
use App\Models\Skill;
use App\Notifications\SwapRequestNotification;
use App\Notifications\SwapAcceptedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwapController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $sentSwaps = Swap::with(['receiver', 'offeredSkill', 'requestedSkill', 'reviews'])
            ->where('sender_id', $user->id)
            ->latest()
            ->get();
        
        $receivedSwaps = Swap::with(['receiver', 'offeredSkill', 'requestedSkill', 'reviews'])
            ->where('receiver_id', $user->id)
            ->latest()
            ->get();
        
        return view('swaps.index', compact('sentSwaps', 'receivedSwaps'));
    }

    public function create($userId)
    {
        $receiver = User::with(['offeredSkills', 'soughtSkills'])->findOrFail($userId);
        $sender = Auth::user()->load(['offeredSkills', 'soughtSkills']);
        
        return view('swaps.create', compact('receiver', 'sender'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'offered_skill_id' => ['required', 'exists:skills,id'],
            'requested_skill_id' => ['required', 'exists:skills,id'],
        ]);

        $exists = Swap::where('sender_id', Auth::id())
            ->where('receiver_id', $request->receiver_id)
            ->where('offered_skill_id', $request->offered_skill_id)
            ->where('requested_skill_id', $request->requested_skill_id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Swap request sudah ada!');
        }

        $swap = Swap::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'offered_skill_id' => $request->offered_skill_id,
            'requested_skill_id' => $request->requested_skill_id,
            'status' => 'pending',
        ]);

        $receiver = User::find($request->receiver_id);
        $receiver->notify(new SwapRequestNotification($swap));

        return redirect()->route('swaps.index')->with('success', 'Swap request berhasil dikirim!');
    }

    public function accept($id)
    {
        $swap = Swap::where('id', $id)
            ->where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $swap->update([
            'status' => 'accepted',
            'swapped_at' => now(),
        ]);

        $swap->sender->notify(new SwapAcceptedNotification($swap));

        return back()->with('success', 'Swap request diterima!');
    }

    public function reject($id)
    {
        $swap = Swap::where('id', $id)
            ->where(function ($query) {
                $query->where('sender_id', Auth::id())
                      ->orWhere('receiver_id', Auth::id());
            })
            ->where('status', 'pending')
            ->firstOrFail();

        $isSender = $swap->sender_id === Auth::id();
        $swap->update(['status' => 'rejected']);

        $message = $isSender ? 'Swap request berhasil dibatalkan!' : 'Swap request ditolak!';
        return back()->with('success', $message);
    }

    public function complete($id)
    {
        $swap = Swap::where('id', $id)
            ->where(function ($query) {
                $query->where('sender_id', Auth::id())
                    ->orWhere('receiver_id', Auth::id());
            })
            ->where('status', 'accepted')
            ->firstOrFail();

        $swap->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Notify the other party
        $otherUser = $swap->sender_id === Auth::id() ? $swap->receiver : $swap->sender;
        $otherUser->notify(new \App\Notifications\SwapCompletedNotification($swap));

        return back()->with('success', 'Swap ditandai selesai! Sekarang Anda bisa memberikan review.');
    }
}
