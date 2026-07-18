<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_subscription_id')->nullable();
            $table->integer('amount');
            $table->enum('status', ['pending', 'settlement', 'failed', 'expired', 'cancelled', 'denied'])->default('pending');
            $table->enum('plan', ['free', 'pro'])->default('pro');
            $table->json('midtrans_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};