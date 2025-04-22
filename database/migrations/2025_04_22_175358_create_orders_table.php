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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable() //In case it will be requested to remove user from table
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('product_id')
                ->nullable()
                ->constrained(); //No cascade clause, not to have orphan orders
            $table->unsignedInteger('quantity');
            $table->decimal('total', 10, 2);
            $table->string('user_name');
            $table->string('user_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
