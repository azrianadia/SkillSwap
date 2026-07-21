<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Carbon\Carbon;

$user = App\Models\User::first();
$price = config('subscription.plans.pro.price');

// Try with subscription_ prefix format (which Midtrans docs suggest)
$subscriptionToken = 'SUB-' . $user->id . '-' . Str::upper(Str::random(12)) . '-' . time();

$params = [
    'subscription_token' => 'SUB-' . $user->id . '-' . Str::upper(Str::random(12)) . '-' . time(),
    'subscription_payment_type' => 'credit_card',
    'subscription_amount' => config('subscription.plans.pro.price'),
    'subscription_pricing_model' => 'fixed',
    'subscription_name' => 'KolaboKampus Pro Monthly',
    'subscription_description' => 'Langganan bulanan Pro KolaboKampus',
    'customer_details' => [
        'first_name' => $user->name,
        'email' => $user->email,
        'phone' => $user->whatsapp_number ?? '',
    ],
    'schedule' => [
        'interval' => 1,
        'interval_unit' => 'month',
        'max_interval' => 0,
        'start_time' => Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s O'),
    ],
    'pricing_model' => 'fixed',
    'price' => config('subscription.plans.pro.price'),
    'name' => 'KolaboKampus Pro Monthly',
    'description' => 'Langganan bulanan Pro KolaboKampus',
    'metadata' => [
        'user_id' => $user->id,
        'plan' => 'pro',
    ],
];

// Convert to form data
$postFields = http_build_query($params);

echo "Form data being sent:\n$postFields\n\n";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api.sandbox.midtrans.com/v1/subscriptions',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postFields,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json',
        'Authorization: Basic ' . base64_encode(config('services.midtrans.server_key') . ':'),
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";