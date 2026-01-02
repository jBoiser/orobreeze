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
        Schema::table('suppliers', function (Blueprint $table) {
            // Re-declare the types and add nullable()
            $table->string('office_contact_number')->nullable()->change();
            $table->string('office_email_address')->nullable()->change();
            $table->text('office_address')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // To undo, switch back to NOT nullable
            $table->string('office_contact_number')->nullable(false)->change();
            $table->string('office_email_address')->nullable(false)->change();
            $table->text('office_address')->nullable(false)->change();
        });
    }
};
