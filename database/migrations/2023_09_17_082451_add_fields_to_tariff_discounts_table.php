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
        Schema::table('tariff_discounts', function (Blueprint $table) {
            $table->jsonb('conditions')->after('id');
            $table->string('description', 2048)->after('id');
            $table->string('name')->after('id');
            $table->dateTime('expired_at')->after('id');
            $table->unsignedBigInteger('company_id')->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tariff_discounts', function (Blueprint $table) {
            $table->dropColumn('conditions');
            $table->dropColumn('description', 2048);
            $table->dropColumn('name');
            $table->dropColumn('expired_at');
            $table->dropColumn('company_id');
        });
    }
};
