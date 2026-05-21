<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->nullable()->constrained()->cascadeOnDelete();

            $table->enum('type', ['waiting','standby']); // 🔥 jenis kursi
            $table->integer('seat_number'); // nomor kursi

            $table->enum('status', ['empty','filled'])->default('empty');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};