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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->index();
            $table->unsignedBigInteger('created_user_id')->index();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('bus_route_id')->index();
            $table->string('veh_number')->nullable();
            $table->dateTime('started_at')->useCurrent()->index();
            $table->dateTime('finished_at')->useCurrent()->index();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('created_user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('bus_route_id')->references('id')->on('bus_routes')->cascadeOnUpdate()->cascadeOnDelete();

            $table->index(['started_at', 'finished_at']);
            $table->index(['company_id', 'bus_route_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
