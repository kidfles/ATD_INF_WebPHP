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
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->enum('wear_and_tear_policy', ['none', 'fixed', 'percentage'])->default('none')->after('id'); // After ID or wherever suitable
            $table->decimal('wear_and_tear_value', 10, 2)->default(0.00)->after('wear_and_tear_policy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn(['wear_and_tear_policy', 'wear_and_tear_value']);
        });
    }
};
