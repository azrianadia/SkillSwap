<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSkillController;
use App\Http\Controllers\SwapController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/profile/skills', [UserSkillController::class, 'store'])->name('profile.skills.store');
    Route::delete('/profile/skills/{id}', [UserSkillController::class, 'destroy'])->name('profile.skills.destroy');
    
    // Swap routes
    Route::get('/swaps', [SwapController::class, 'index'])->name('swaps.index');
    Route::get('/swaps/create/{userId}', [SwapController::class, 'create'])->name('swaps.create');
    Route::post('/swaps', [SwapController::class, 'store'])->name('swaps.store');
    Route::post('/swaps/{id}/accept', [SwapController::class, 'accept'])->name('swaps.accept');
    Route::post('/swaps/{id}/reject', [SwapController::class, 'reject'])->name('swaps.reject');
    Route::post('/swaps/{id}/complete', [SwapController::class, 'complete'])->name('swaps.complete');
    
    // Review routes
    Route::get('/swaps/{swapId}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');

    // Chat routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{swapId}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{swapId}/poll', [ChatController::class, 'poll'])->name('chat.poll');

    // Subscription routes
    Route::get('/upgrade', [SubscriptionController::class, 'show'])->name('upgrade.show');
    Route::get('/upgrade/confirm', [SubscriptionController::class, 'confirm'])->name('upgrade.confirm');
    Route::post('/upgrade/process', [SubscriptionController::class, 'process'])->name('upgrade.process');
    Route::get('/upgrade/success', [SubscriptionController::class, 'success'])->name('upgrade.success');
    Route::post('/upgrade/cancel', [SubscriptionController::class, 'cancel'])->name('upgrade.cancel');
    Route::post('/upgrade/cancel', [SubscriptionController::class, 'cancel'])->name('upgrade.cancel');
    
        // Midtrans Webhooks are defined outside auth middleware
    });

Route::get('/upgrade/status', [SubscriptionController::class, 'status'])->name('upgrade.status');

// Webhook routes (no auth required)
Route::post('/midtrans/callback', [SubscriptionController::class, 'callback'])->name('midtrans.callback');
Route::post('/midtrans/subscription', [SubscriptionController::class, 'subscriptionCallback'])->name('midtrans.subscription');

require __DIR__.'/auth.php';
