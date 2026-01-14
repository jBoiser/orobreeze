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
        Schema::table('job_orders', function (Blueprint $table) {
            $table->decimal('downpayment', 15, 2)->default(0)->after('project_cost');
            $table->string('payment_status')->default('pending')->after('downpayment'); // pending, downpayment, paid
            $table->string('task_status')->default('onGoing')->after('payment_status'); // onGoing, onHold, completed, Cancelled
        });
    }

    public function down(): void
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropColumn(['downpayment', 'payment_status', 'task_status']);
        });
    }
};
