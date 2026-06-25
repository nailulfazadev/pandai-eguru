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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'Saran', 'Laporan Bug', 'Saran Fitur', 'Kritik', 'Lainnya'
            $table->string('subject');
            $table->text('message');
            $table->text('response')->nullable();
            $table->string('status')->default('Menunggu Tanggapan'); // 'Menunggu Tanggapan', 'Dijawab', 'Selesai'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
