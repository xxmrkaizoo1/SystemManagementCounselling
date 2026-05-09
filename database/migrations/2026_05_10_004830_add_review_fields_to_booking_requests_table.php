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
        Schema::table('booking_requests', function (Blueprint $table): void {
            $table->unsignedTinyInteger('review_rating')->nullable()->after('status');
            $table->text('review_comment')->nullable()->after('review_rating');
            $table->timestamp('reviewed_at')->nullable()->after('review_comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table): void {
            $table->dropColumn(['review_rating', 'review_comment', 'reviewed_at']);
        });
    }
};
