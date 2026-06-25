<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'subscription_tier')) {
                $table->string('subscription_tier')->default('trial')->after('password');
            }
            if (!Schema::hasColumn('users', 'subscription_ends_at')) {
                $table->timestamp('subscription_ends_at')->nullable()->after('subscription_tier');
            }
        });

        // Update existing users to have 7 days trial starting from today
        DB::table('users')->whereNull('subscription_ends_at')->update([
            'subscription_ends_at' => now()->addDays(7)
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'subscription_tier')) {
                $table->dropColumn('subscription_tier');
            }
            if (Schema::hasColumn('users', 'subscription_ends_at')) {
                $table->dropColumn('subscription_ends_at');
            }
        });
    }
};
