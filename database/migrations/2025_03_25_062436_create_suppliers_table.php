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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('supplier_id'); // Auto-incrementing primary key
            $table->string('supplier_name'); // Supplier name (varchar)
            $table->string('item_provider'); // Item provider (varchar)
            $table->integer('order_item'); // Order item count (integer)
            $table->date('order_date'); // Order date (date)
            $table->enum('status', ['pending', 'active']); // Status field (enum)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
