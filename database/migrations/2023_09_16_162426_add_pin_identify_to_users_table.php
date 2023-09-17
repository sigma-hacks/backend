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
        Schema::table('users', function (Blueprint $table) {
            $table->string('identify', 1024)->default(null)->nullable()->after('company_id')->index();
            $table->string('employee_card')->default(null)->nullable()->after('name')->index();
            $table->string('pin', 1024)->default(null)->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('employee_card');
            $table->dropColumn('identify');
            $table->dropColumn('pin');
        });
    }
};
