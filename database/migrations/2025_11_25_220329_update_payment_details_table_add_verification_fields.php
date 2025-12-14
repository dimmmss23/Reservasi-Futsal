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
        Schema::table('payment_details', function (Blueprint $table) {
            // Rename status to payment_status
            $table->renameColumn('status', 'payment_status');
            
            // Add verification fields
            $table->timestamp('verified_at')->nullable()->after('payment_proof');
            $table->string('verified_by')->nullable()->after('verified_at');
        });
        
        // Update enum values for payment_status
        DB::statement("ALTER TABLE payment_details MODIFY payment_status ENUM('pending', 'verified', 'rejected', 'success', 'failed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_details', function (Blueprint $table) {
            // Drop verification fields
            $table->dropColumn(['verified_at', 'verified_by']);
            
            // Rename back
            $table->renameColumn('payment_status', 'status');
        });
    }
};
