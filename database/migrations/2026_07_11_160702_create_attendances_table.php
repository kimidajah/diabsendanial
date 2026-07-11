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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('scan_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('date');
            $table->time('check_in')->nullable(); // nullable karena izin/sakit/alfa tidak ada check_in
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'sakit', 'alfa']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
