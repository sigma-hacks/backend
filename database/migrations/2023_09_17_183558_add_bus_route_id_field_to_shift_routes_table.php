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
        Schema::table('shift_routes', function (Blueprint $table) {
            $table->unsignedBigInteger('bus_router_id')->nullable()->default(null)->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_routes', function (Blueprint $table) {
            $table->dropColumn('bus_router_id');
        });
    }
};
