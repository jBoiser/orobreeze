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
        Schema::create('installations', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('job_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('model_name')->nullable()->default('-');
            $table->string('outdoor_model')->nullable()->default('-');
            $table->string('unit_type')->nullable(); 
            $table->decimal('srp', 12, 2)->nullable();
            $table->string('refrigerant_type')->nullable();
            $table->boolean('is_inverter')->default(true);
            $table->text('description')->nullable();
            $table->string('hp_capacity')->nullable();
            $table->enum('service_by', ['Team A', 'Team B'])->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['onHold', 'onGoing', 'Cancelled'])->default('onHold');
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installations');
    }
};
