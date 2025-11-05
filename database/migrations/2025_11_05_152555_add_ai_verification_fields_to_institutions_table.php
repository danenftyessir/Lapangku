<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            // AI Verification Status
            $table->string('verification_status')->default('pending_verification')->after('is_verified');
            // Status: pending_verification, needs_review, pending_payment, active, suspended, rejected

            $table->decimal('verification_score', 5, 2)->nullable()->after('verification_status');
            $table->decimal('verification_confidence', 3, 2)->nullable()->after('verification_score');
            $table->text('rejection_reason')->nullable()->after('verification_confidence');
            $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');

            // Add foreign key
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');

            // Add index
            $table->index('verification_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn([
                'verification_status',
                'verification_score',
                'verification_confidence',
                'rejection_reason',
                'verified_by'
            ]);
        });
    }
};
