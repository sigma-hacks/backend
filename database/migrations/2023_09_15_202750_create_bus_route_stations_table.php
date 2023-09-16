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
        Schema::create('bus_route_stations', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('bus_route_id');
            $table->string('name');
            $table->unsignedInteger('sort')->default(100);
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('distance')->default(0);
            $table->unsignedInteger('map_lat')->default(null)->nullable();
            $table->unsignedInteger('map_lng')->default(null)->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('bus_route_id')->references('id')->on('bus_routes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('but_route_stations');
    }
};
