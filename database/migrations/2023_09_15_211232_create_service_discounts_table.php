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
        Schema::create('service_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_user_id')->index();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedInteger('tariff_id')->index();
            $table->unsignedInteger('service_id')->index();
            $table->boolean('is_active')->default(1);
            $table->string('description', 2048)->nullable()->default(null);
            $table->string('name')->nullable()->default(null);
            $table->unsignedInteger('amount')->default(0);
            $table->string('discount_type')->default('fixed'); // fixed | percent
            $table->dateTime('started_at')->nullable()->default(null)->index();
            $table->dateTime('finished_at')->nullable()->default(null)->index();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_discounts');
    }
};
