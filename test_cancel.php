<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::find(13);
echo "User: " . $user->id . PHP_EOL;
echo "Is Pro: " . ($user->is_pro ? 'yes' : 'no') . PHP_EOL;
echo "midtrans_subscription_id (user): " . $user->midtrans_subscription_id . PHP_EOL;
$tx = $user->transactions()->latest()->first();
if ($tx) {
    echo "Transaction ID: " . $tx->id . PHP_EOL;
    echo "Order ID: " . $tx->order_id . PHP_EOL;
    echo "Midtrans Subscription ID: " . $tx->midtrans_subscription_id . PHP_EOL;
    echo "Status: " . $tx->status . PHP_EOL;
    echo "Plan: " . $tx->plan . PHP_EOL;
    echo "Response: " . json_encode($tx->midtrans_response) . PHP_EOL;
} else {
    echo "No transaction found" . PHP_EOL;
}