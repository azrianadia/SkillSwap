<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('badge')->nullable()->after('midtrans_subscription_id');
            $table->enum('support_level', ['normal', 'priority'])->default('normal')->after('badge');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['badge', 'support_level']);
        });
    }
};
