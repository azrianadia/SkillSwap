<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\User;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule commands
Schedule::call(function () {
    // Reset monthly quota for free users (daily at 00:05)
    User::where('plan', 'free')
        ->where('is_pro', false)
        ->where('quota_reset_at', '<=', now())
        ->chunkById(100, function ($users) {
            foreach ($users as $user) {
                $user->resetMonthlyQuota();
            }
        });
})->dailyAt('00:05')->timezone('Asia/Jakarta');

// Check expired pro subscriptions (daily at 01:00)
Schedule::call(function () {
    User::where('is_pro', true)
        ->where('quota_reset_at', '<=', now())
        ->chunkById(100, function ($users) {
            foreach ($users as $user) {
                if (!$user->midtrans_subscription_id || !$user->isSubscriptionActive()) {
                    $user->update([
                        'is_pro' => false,
                        'plan' => 'free',
                        'swap_quota' => config('subscription.plans.free.swap_limit'),
                        'quota_reset_at' => now()->addMonth(),
                        'midtrans_subscription_id' => null,
                    ]);
                }
            }
        });
})->dailyAt('01:00')->timezone('Asia/Jakarta');
