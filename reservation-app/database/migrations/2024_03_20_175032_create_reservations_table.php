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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->text('additional_request');
            $table->integer('number_of_guests');
            $table->boolean('payment_status')->comment('0=not paid, 1=paid, 2=advanced paid, 3=pending');
            $table->decimal('sub_amount', 10,2)->nullable()->comment('LKR');
            $table->decimal('discount', 10,2)->nullable()->comment('LKR');
            $table->decimal('vat', 10,2)->nullable()->comment('LKR');
            $table->decimal('net_amount', 10,2)->nullable()->comment('LKR');
            $table->decimal('total_paid_amount', 10,2)->nullable()->comment('LKR');
            $table->boolean('status')->default(1)->comment('0=disabled, 1=activated, 2=draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
