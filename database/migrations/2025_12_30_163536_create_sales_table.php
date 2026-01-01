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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId(column: 'payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal(column: 'total', total: 8, places: 2);
            $table->decimal(column: 'paid_amount', total: 8, places: 2);
            $table->decimal(column: 'discount', total: 8, places: 2)->default(0.00); //flat discount amount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
