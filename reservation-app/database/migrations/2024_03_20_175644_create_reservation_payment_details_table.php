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
        Schema::create('reservation_payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->boolean('payment_method')->comment('0=cash, 1=bank transfer, 2=card');
            $table->boolean('payment_status')->comment('0=additional, 1=full, 2=advanced');
            $table->decimal('paid_amount', 10,2)->nullable()->comment('LKR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_payment_details');
    }
};
