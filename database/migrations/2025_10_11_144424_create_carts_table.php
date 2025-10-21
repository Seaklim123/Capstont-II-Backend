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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('note')->nullable();
            $table->integer('quantity')->default(1);
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('table_id')->constrained('table_numbers');
            $table->enum('status', ['starting','ordering'])->default('starting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
