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
        Schema::create('card_tariffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_user_id')->index();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('name');
            $table->unsignedInteger('amount')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_tariffs');
    }
};
