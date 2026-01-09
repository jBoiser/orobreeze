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
         Schema::create('installation_prices', function (Blueprint $table) {
            $table->id();
            $table->string('hp_capacity'); // e.g., "1.5 HP" or "2.0 HP"
            $table->string('installation_type');
            $table->decimal('price', 10, 2);
            $table->softDeletes(); // Required for SoftDelete functionality
            $table->timestamps();
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
