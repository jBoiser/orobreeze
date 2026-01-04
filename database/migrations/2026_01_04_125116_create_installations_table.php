<?php

use Illuminate\Database\Eloquent\SoftDeletes;
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
            $table->id()->unique();
            $table->foreignId('job_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('installation_address');
            $table->string('unit_type');
            $table->string('unit_model')->nullable();
            $table->string('model_indoor')->nullable();
            $table->string('model_outdoor')->nullable();
            $table->string('unit_capacity');
            $table->string('refregirant_type');
            $table->decimal('discount', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->text('description')->nullable();
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
