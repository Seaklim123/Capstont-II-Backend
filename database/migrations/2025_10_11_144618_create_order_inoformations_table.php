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
            $table->bigInteger('numberOrder');
            $table->double('totalPrice')->default(0)->nullable();
            $table->double('discount')->default(0)->nullable();
            $table->longText('note')->nullable();
            $table->double('refund')->default(0)->nullable();
            $table->string('phone_number')->nullable();
            $table->enum('status', ['starting', 'accepted', 'cancel']);
            $table->enum('payment', ['card', 'cash'])->default('cash');
            $table->enum('payment_status', ['done', 'nondone'])->default('nondone');
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
