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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'quantity', 'total']);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('product_id')
                ->nullable()
                ->constrained(); //No cascade clause, not to have orphan orders
            $table->unsignedInteger('quantity');
            $table->decimal('total', 10, 2);
            $table->dropColumn('total');
            $table->dropColumn('status');
        });
    }
};
