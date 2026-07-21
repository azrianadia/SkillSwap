<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::first();
$s = new App\Services\MidtransService();
try {
    $result = $s->createSubscription($user);
    echo 'SUCCESS: ' . json_encode($result) . PHP_EOL;
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}