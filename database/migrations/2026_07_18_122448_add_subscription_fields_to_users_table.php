<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('plan', ['free', 'pro'])->default('free')->after('avatar');
            $table->integer('swap_quota')->default(5)->after('plan');
            $table->timestamp('quota_reset_at')->nullable()->after('swap_quota');
            $table->boolean('is_pro')->default(false)->after('quota_reset_at');
            $table->string('midtrans_customer_id')->nullable()->after('is_pro');
            $table->string('midtrans_subscription_id')->nullable()->after('midtrans_customer_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'plan',
                'swap_quota',
                'quota_reset_at',
                'is_pro',
                'midtrans_customer_id',
                'midtrans_subscription_id',
            ]);
        });
    }
};