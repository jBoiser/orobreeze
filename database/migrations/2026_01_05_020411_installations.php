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
        // Migration for Installations
        Schema::create('installations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_order_id')->constrained()->cascadeOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('service_by');
            $table->string('status')->default('onHold');
            $table->decimal('total_price', 12, 2)->default(0); // Grand total
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Migration for Installation Items (The Repeater data)
        Schema::create('installation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->string('model_name');
            $table->string('unit_type');
            $table->string('refrigerant_type')->nullable();
            $table->string('hp_capacity')->nullable();
            $table->string('outdoor_model')->nullable();
            $table->decimal('srp', 12, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->decimal('discount', 5, 2)->default(0); // Percentage
            $table->decimal('price', 12, 2)->default(0);
            $table->softDeletes(); // Subtotal for this row
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
