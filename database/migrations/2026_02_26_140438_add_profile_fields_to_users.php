<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('name');
            $table->string('phone', 30)->nullable()->after('full_name');
            $table->string('years', 50)->nullable()->after('password');
            $table->string('programme', 50)->nullable()->after('years');
            $table->string('profile_pic')->nullable()->after('programme');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'phone', 'years', 'programme', 'profile_pic']);
        });
    }
};
