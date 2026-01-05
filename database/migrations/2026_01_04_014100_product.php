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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('model_name');
            $table->string('slug')->unique();
            $table->string('hp_capacity');
            $table->string('unit_type');
            $table->boolean('is_inverter')->default(true);
            $table->decimal('srp', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->enum('refrigerant_type',['R410A', 'R32'])->nullable();
            $table->string('outdoor_model')->nullable()->default('-');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
