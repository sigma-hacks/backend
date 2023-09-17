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
        Schema::table('card_checks', function (Blueprint $table) {
            $table->unsignedBigInteger('shift_id')->index();
            $table->unsignedBigInteger('shift_route_id')->index();
            $table->unsignedBigInteger('bus_route_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('card_checks', function (Blueprint $table) {
            $table->dropColumn('shift_id');
            $table->dropColumn('shift_route_id');
            $table->dropColumn('bus_route_id');
        });
    }
};
