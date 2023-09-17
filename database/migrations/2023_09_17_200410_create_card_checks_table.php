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
        Schema::create('card_checks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employer_id')->index();
            $table->unsignedBigInteger('card_id')->index();
            $table->unsignedBigInteger('company_id')->index();
            $table->double('pos_lat')->index();
            $table->double('pos_lng')->index();

            $table->dateTime('checked_at')->useCurrent();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['company_id', 'employer_id']);
            $table->index(['created_at', 'checked_at']);
            $table->index(['checked_at', 'updated_at']);
            $table->index(['pos_lat', 'pos_lng']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_checks');
    }
};
