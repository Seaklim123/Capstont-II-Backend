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
        Schema::create('order_informations', function (Blueprint $table) {
            $table->id();
            $table->integer('numberOrder');
            $table->double('totalPrice')->default(0)->nullable();
            $table->double('discount')->default(0)->nullable();
            $table->enum('status', ['starting', 'accepted', 'cancel'])->default('starting');
            $table->enum('payment', ['card', 'cash'])->default('cash');
            $table->longText('note')->nullable();
            $table->double('')->default(0)->nullable();
            $table->string('phone_number')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_informations');
    }
};
