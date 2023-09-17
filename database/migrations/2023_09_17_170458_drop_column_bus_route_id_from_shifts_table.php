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
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropForeign('shifts_bus_route_id_foreign');
            $table->dropIndex('shifts_bus_route_id_index');
            $table->dropColumn('bus_route_id');
            $table->dropColumn('veh_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->unsignedBigInteger('bus_route_id')->index();
            $table->string('veh_number')->nullable();
        });
    }
};
