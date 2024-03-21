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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->integer('number_of_guests');
            $table->string('room_size');
            $table->boolean('breakfirst_included')->comment('0=no, 1=yes');
            $table->string('main_image');
            $table->decimal('price_amt', 10,2)->nullable()->comment('LKR');
            $table->decimal('net_services_amt', 10,2)->nullable()->comment('LKR');
            $table->decimal('total_amt', 10,2)->nullable()->comment('LKR');
            $table->boolean('status')->default(1)->comment('0=disabled, 1=activated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
