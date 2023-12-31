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
        Schema::table('card_tariffs', function (Blueprint $table) {
            $table->jsonb('conditions')->after('amount');
            $table->boolean('is_active')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('card_tariffs', function (Blueprint $table) {
            $table->dropColumn('conditions');
            $table->dropColumn('is_active');
        });
    }
};
