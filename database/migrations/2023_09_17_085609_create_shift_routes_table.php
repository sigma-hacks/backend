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
        Schema::create('shift_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shift_id');
            $table->foreign('shift_id')->on('shifts')->references('id')
                ->cascadeOnUpdate();

            $table->unsignedBigInteger('employer_id');
            $table->foreign('employer_id')->on('users')->references('id')
                ->cascadeOnUpdate();

            $table->string('vehicle_number');
            $table->float('pos_lat')->nullable();
            $table->float('pos_lng')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['pos_lat', 'pos_lng']);
            $table->index(['started_at', 'finished_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_routes');
    }
};
