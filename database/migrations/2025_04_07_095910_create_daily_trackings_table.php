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
        Schema::create('daily_trackings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('item_id'); // inventory id
            $table->string('day_number');
            $table->integer('projected_usage')->nullable();
            $table->integer('buffer_percentage')->default(0);
            $table->integer('picked')->default(0);
            $table->integer('used')->nullable();
            $table->integer('remaining_start')->default(0);
            $table->integer('remaining_end')->nullable();
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('inventories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_trackings');
    }
};
