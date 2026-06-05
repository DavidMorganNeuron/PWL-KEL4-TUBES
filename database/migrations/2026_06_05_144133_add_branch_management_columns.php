<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('is_always_open');
            $table->boolean('is_closing')->default(false)->after('is_active');
            $table->softDeletes()->after('is_closing');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('is_closing');
            $table->dropColumn('is_active');
        });
    }
};
