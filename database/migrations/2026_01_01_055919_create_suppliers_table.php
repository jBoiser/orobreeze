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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_id')->unique();
            $table->string('company_name');
            $table->string('name'); // Sales Rep
            $table->string('contact_number'); // Sales
            $table->string('email_address'); // Sales
            $table->text('office_address');
            $table->string('owner')->nullable();
            $table->string('office_contact_number');
            $table->string('office_email_address'); // office
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
