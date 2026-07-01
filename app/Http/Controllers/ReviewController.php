<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Swap;
use App\Models\User;
use App\Notifications\ReviewReceivedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create($swapId)
    {
        $swap = Swap::with(['sender', 'receiver', 'offeredSkill', 'requestedSkill'])
            ->where('id', $swapId)
            ->whereIn('status', ['accepted', 'completed'])
            ->where(function ($query) {
                $query->where('sender_id', Auth::id())
                    ->orWhere('receiver_id', Auth::id());
            })
            ->firstOrFail();

        $revieweeId = $swap->sender_id === Auth::id() 
            ? $swap->receiver_id 
            : $swap->sender_id;

        $reviewee = $swap->sender_id === Auth::id() 
            ? $swap->receiver 
            : $swap->sender;

        $existingReview = Review::where('swap_id', $swapId)
            ->where('reviewer_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->route('swaps.index')->with('error', 'Anda sudah memberikan review!');
        }

        return view('reviews.create', compact('swap', 'reviewee'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'swap_id' => ['required', 'exists:swaps,id'],
            'reviewee_id' => ['required', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        $swap = Swap::where('id', $request->swap_id)
            ->whereIn('status', ['accepted', 'completed'])
            ->where(function ($query) {
                $query->where('sender_id', Auth::id())
                    ->orWhere('receiver_id', Auth::id());
            })
            ->firstOrFail();

        $existingReview = Review::where('swap_id', $request->swap_id)
            ->where('reviewer_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review!');
        }

        $review = Review::create([
            'swap_id' => $request->swap_id,
            'reviewer_id' => Auth::id(),
            'reviewee_id' => $request->reviewee_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $reviewee = User::find($request->reviewee_id);
        $reviewee->notify(new ReviewReceivedNotification($review));

        return redirect()->route('swaps.index')->with('success', 'Review berhasil dikirim!');
    }
}
