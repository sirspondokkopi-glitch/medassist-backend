<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrument_sets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();              // Auto SET-001, SET-002, ...
            $table->string('name');                        // mis. "Set Bedah Minor"
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->string('status')->default('tersedia'); // mengikuti enum status InstrumentStock
            $table->text('note')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrument_sets');
    }
};
