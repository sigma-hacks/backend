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
        Schema::table('company_services', function (Blueprint $table) {
            $table->text('description')->nullable()->default(null)->after('photo');
            $table->jsonb('conditions')->default('{}')->after('photo');
            $table->unsignedBigInteger('created_user_id')->nullable()->default(null)->index()->after('photo');

            $table->foreign('created_user_id')->on('users')->references('id')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_services', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('conditions');
            $table->dropColumn('created_user_id');
        });
    }
};
