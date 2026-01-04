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
    Schema::table('products', function (Blueprint $table) {
            // 1. Rename 'type' to 'unit_type'
            // Note: Requires 'composer require doctrine/dbal' if on older Laravel, 
            // but Laravel 10/11/12 supports this natively.
            $table->renameColumn('type', 'unit_type');

            // 2. Add the new specific model columns
            $table->string('refrigerant')->nullable()->after('unit_type');
            $table->string('window_model')->nullable()->after('refrigerant');
            $table->string('indoor_model')->nullable()->after('window_model');
            $table->string('outdoor_model')->nullable()->after('indoor_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
