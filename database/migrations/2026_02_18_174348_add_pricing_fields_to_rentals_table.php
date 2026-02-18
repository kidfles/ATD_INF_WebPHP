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
        Schema::table('rentals', function (Blueprint $table) {
            // total_price slaat de basis huurprijs op (duur * dagprijs)
            $table->decimal('total_price', 10, 2)->nullable()->after('end_date');
            // total_cost slaat het uiteindelijke totaalbedrag op (inclusief boetes/slijtage)
            $table->decimal('total_cost', 10, 2)->nullable()->after('total_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['total_price', 'total_cost']);
        });
    }
};
