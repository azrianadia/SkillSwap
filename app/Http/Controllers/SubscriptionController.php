<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MidtransService;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function show()
    {
        $user = Auth::user();
        $quota = $user->getQuotaInfo();
        
        return view('subscription.upgrade', compact('quota'));
    }

    public function upgrade(Request $request)
    {
        $user = Auth::user();
        
        if ($user->is_pro) {
            return back()->with('info', 'Anda sudah berlangganan Pro.');
        }

        try {
            // Create subscription (using Snap API for payment)
            $result = $this->midtrans->createSubscription($user);
            
            // Also create transaction record for tracking
            $orderId = 'ORDER-' . $user->id . '-' . Str::upper(Str::random(8)) . '-' . time();
            Transaction::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'midtrans_subscription_id' => $result['order_id'],
                'amount' => config('subscription.plans.pro.price'),
                'status' => 'pending',
                'plan' => 'pro',
                'expired_at' => now()->addHours(24),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'snap_token' => $result['snap_token'],
                    'redirect_url' => $result['redirect_url'],
                ]);
            }

            return view('subscription.snap', [
                'snapToken' => $result['snap_token'],
                'redirectUrl' => $result['redirect_url'],
            ]);

        } catch (\Exception $e) {
            Log::error('Upgrade failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses upgrade. Silakan coba lagi.');
        }
    }

public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        $transaction = Transaction::where('order_id', $orderId)->first();
        
        // If the transaction is settled, activate Pro for the user
        if ($transaction && in_array($transaction->status, ['settlement', 'capture'])) {
            $user = Auth::user();
            $user->update([
                'is_pro' => true,
                'plan' => 'pro',
                'swap_quota' => -1,
                'quota_reset_at' => now()->addMonth(),
                'badge' => 'Pro',
                'support_level' => 'priority',
            ]);
        } elseif ($transaction && $transaction->status !== 'settlement') {
            // Fallback: query Midtrans for latest status
            try {
                $statusData = $this->midtrans->getTransactionStatus($orderId);
                $status = $statusData['transaction_status'] ?? null;
                if (in_array($status, ['settlement', 'capture'])) {
                    $transaction->update([
                        'status' => 'settlement',
                        'midtrans_response' => $statusData,
                        'paid_at' => now(),
                    ]);
                    $user = Auth::user();
                    $user->update([
                        'is_pro' => true,
                        'plan' => 'pro',
                        'swap_quota' => -1,
                        'quota_reset_at' => now()->addMonth(),
                        'badge' => 'Pro',
                        'support_level' => 'priority',
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Midtrans status check failed: ' . $e->getMessage());
            }
}

        return view('subscription.success', compact('transaction'));
    }

    public function confirm()
    {
        $user = Auth::user();
        $quota = $user->getQuotaInfo();
        $price = config('subscription.plans.pro.price');
        
        return view('subscription.confirm', compact('user', 'quota', 'price'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        
        if ($user->is_pro) {
            return back()->with('info', 'Anda sudah berlangganan Pro.');
        }

        try {
            $result = $this->midtrans->createSubscription($user);
            
            $orderId = 'ORDER-' . $user->id . '-' . Str::upper(Str::random(8)) . '-' . time();
            Transaction::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'midtrans_subscription_id' => $result['order_id'],
                'amount' => config('subscription.plans.pro.price'),
                'status' => 'pending',
                'plan' => 'pro',
                'expired_at' => now()->addHours(24),
            ]);

            // Redirect directly to Midtrans payment page
            return redirect($result['redirect_url']);
            
        } catch (\Exception $e) {
            Log::error('Upgrade failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses upgrade. Silakan coba lagi.');
        }
    }

    public function callback(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Callback', $payload);
        
        try {
            $this->midtrans->handleNotification($payload);
        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage());
        }
        
        return response()->json(['status' => 'ok']);
    }

    public function subscriptionCallback(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Subscription Callback', $payload);
        
        try {
            $this->midtrans->handleSubscriptionNotification($payload);
        } catch (\Exception $e) {
            Log::error('Midtrans subscription callback error: ' . $e->getMessage());
        }
        
        return response()->json(['status' => 'ok']);
    }
}