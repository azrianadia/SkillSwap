<?php

namespace App\Services;

require_once base_path('vendor/midtrans/midtrans-php/Midtrans.php');

use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapToken(User $user, string $plan = 'pro'): array
    {
        $price = config("subscription.plans.{$plan}.price");
        $orderId = 'ORDER-' . $user->id . '-' . Str::upper(Str::random(8)) . '-' . time();

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => $price,
            'status' => 'pending',
            'plan' => $plan,
            'expired_at' => now()->addHours(24),
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->whatsapp_number ?? '',
            ],
            'item_details' => [[
                'id' => $plan,
                'price' => $price,
                'quantity' => 1,
                'name' => "Langganan {$plan} - KolaboKampus",
            ]],
            'callbacks' => [
                'finish' => route('upgrade.success'),
                'error' => route('upgrade.show'),
                'pending' => route('upgrade.show'),
            ],
            'expiry' => [
                'start_time' => Carbon::now()->format('Y-m-d H:i:s T'),
                'unit' => 'hours',
                'duration' => 24,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return [
            'snap_token' => $snapToken,
            'redirect_url' => Snap::getRedirectUrl($params),
            'order_id' => $orderId,
        ];
    }

public function createSubscription(User $user): array
    {
        $price = config('subscription.plans.pro.price');
        
        // Generate a unique order ID for the subscription
        $orderId = 'SUB-' . $user->id . '-' . Str::upper(Str::random(12)) . '-' . time();
        
        // Create a transaction record
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => $price,
            'status' => 'pending',
            'plan' => 'pro',
            'expired_at' => now()->addHours(24),
        ]);

        // Use Snap API for subscription payment
        // This creates a payment page that handles the subscription
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->whatsapp_number ?? '',
            ],
            'item_details' => [[
                'id' => 'pro_subscription',
                'price' => $price,
                'quantity' => 1,
                'name' => 'KolaboKampus Pro Monthly',
            ]],
            'callbacks' => [
                'finish' => route('upgrade.success'),
                'error' => route('upgrade.show'),
                'pending' => route('upgrade.show'),
            ],
            'expiry' => [
                'start_time' => Carbon::now()->format('Y-m-d H:i:s T'),
                'unit' => 'hours',
                'duration' => 24,
            ],
            'metadata' => [
                'user_id' => $user->id,
                'plan' => 'pro',
                'is_subscription' => true,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return [
'snap_token' => $snapToken,
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v4/redirection/' . $snapToken,
            'order_id' => $orderId,
        ];
    }
    
    public function handleNotification(array $payload): void
    {
        Log::info('Midtrans webhook received', $payload);
        
        $signatureKey = config('services.midtrans.server_key');
        $expectedSignature = hash('sha512', $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $signatureKey);

        if ($expectedSignature !== $payload['signature_key']) {
            Log::warning('Midtrans: Invalid signature', $payload);
            return;
        }

        $orderId = $payload['order_id'];
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            Log::warning('Midtrans: Transaction not found', ['order_id' => $orderId]);
            return;
        }

        $transaction->update([
            'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
            'midtrans_response' => $payload,
            'status' => $this->mapMidtransStatus($payload['transaction_status']),
        ]);

        if (in_array($payload['transaction_status'], ['settlement', 'capture'])) {
            $transaction->update(['paid_at' => now()]);
            $this->activateProSubscription($transaction->user, $payload);
        }
    }

    public function handleSubscriptionNotification(array $payload): void
    {
        $subscriptionId = $payload['subscription_id'] ?? null;
        $user = User::where('midtrans_subscription_id', $subscriptionId)->first();

        if (!$user) {
            Log::warning('Midtrans Subscription: User not found', ['subscription_id' => $subscriptionId]);
            return;
        }

        $status = $payload['status'] ?? '';
        
        switch ($status) {
            case 'active':
            case 'past_due':
                $user->update([
                    'is_pro' => true,
                    'plan' => 'pro',
                    'swap_quota' => -1,
                    'quota_reset_at' => Carbon::parse($payload['next_billing_date'] ?? '+1 month'),
                ]);
                break;
            case 'cancelled':
            case 'expired':
            case 'failed':
                $this->downgradeToFree($user);
                break;
        }

        Transaction::create([
            'user_id' => $user->id,
            'order_id' => 'SUB-' . $subscriptionId . '-' . time(),
            'midtrans_subscription_id' => $subscriptionId,
            'amount' => $payload['gross_amount'] ?? config('subscription.plans.pro.price'),
            'status' => $this->mapMidtransStatus($status),
            'plan' => 'pro',
            'midtrans_response' => $payload,
            'paid_at' => in_array($status, ['active', 'past_due']) ? now() : null,
        ]);
    }

    private function mapMidtransStatus(string $status): string
    {
        return match($status) {
            'capture', 'settlement' => 'settlement',
            'pending' => 'pending',
            'deny', 'cancel', 'expire' => 'cancelled',
            'active', 'past_due' => 'settlement',
            'cancelled', 'expired', 'failed' => 'cancelled',
            default => 'failed',
        };
    }

    private function activateProSubscription(User $user, array $payload): void
    {
        $user->update([
            'is_pro' => true,
            'plan' => 'pro',
            'swap_quota' => -1,
            'quota_reset_at' => Carbon::now()->addMonth(),
            'midtrans_customer_id' => $payload['customer_id'] ?? null,
            'badge' => 'Pro',
            'support_level' => 'priority',
        ]);
    }

    private function downgradeToFree(User $user): void
    {
        $user->update([
            'is_pro' => false,
            'plan' => 'free',
            'swap_quota' => config('subscription.plans.free.swap_limit'),
            'quota_reset_at' => now()->addMonth(),
            'midtrans_subscription_id' => null,
            'badge' => null,
            'support_level' => 'normal',
        ]);
    }

public function getTransactionStatus(string $orderId): array
    {
        $serverKey = config('services.midtrans.server_key');
        $isProduction = config('services.midtrans.is_production', false);
        $baseUrl = $isProduction 
            ? 'https://api.midtrans.com/v2' 
            : 'https://api.sandbox.midtrans.com/v2';
        
        $response = Http::withBasicAuth($serverKey, '')
            ->get("{$baseUrl}/{$orderId}/status");
        
        if ($response->successful()) {
            return $response->json();
        }
        
        Log::error('Midtrans status check failed', [
            'order_id' => $orderId,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        
        return [];
    }
}