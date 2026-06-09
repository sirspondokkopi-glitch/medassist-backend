<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrument_set_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_set_id')->constrained('instrument_sets')->cascadeOnDelete();
            $table->foreignId('instrument_stock_id')->constrained('instrument_stocks')->restrictOnDelete();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->unique(['instrument_set_id', 'instrument_stock_id'], 'set_items_set_stock_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrument_set_items');
    }
};
