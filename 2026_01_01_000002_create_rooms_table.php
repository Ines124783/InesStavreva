<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->foreignId('type_id')->constrained('room_types')->onDelete('cascade');
            $table->integer('floor');
            $table->integer('capacity');
            $table->decimal('price_per_night', 10, 2);
            $table->enum('status', ['available', 'occupied', 'cleaning', 'maintenance'])
                  ->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
