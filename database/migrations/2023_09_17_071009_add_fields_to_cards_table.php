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
        Schema::table('cards', function (Blueprint $table) {
            $table->dateTime('expired_at')->nullable()->default(null)->after('id')->index();
            $table->unsignedBigInteger('identifier')->after('id')->index();
            $table->dateTime('tariff_expired_at')->nullable()->default(null)->after('id')->index();
            $table->unsignedBigInteger('tariff_id')->nullable()->default(null)->after('id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->default(null)->after('id')->index();
            $table->boolean('is_active')->default(false)->after('id')->index();

            $table->index(['created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn('expired_at');
            $table->dropColumn('identifier');
            $table->dropColumn('tariff_expired_at');
            $table->dropColumn('tariff_id');
            $table->dropColumn('user_id');
            $table->dropColumn('is_active');
        });
    }
};
