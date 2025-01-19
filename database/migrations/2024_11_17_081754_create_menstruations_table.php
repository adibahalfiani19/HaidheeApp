<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenstruationsTable extends Migration
{
    public function up()
    {
        Schema::create('menstruations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            $table->date('start_date'); // Tanggal mulai
            $table->time('start_time'); // Waktu mulai
            $table->string('prayer_start')->nullable(); // Sholat yang belum saat mulai
            $table->date('end_date')->nullable(); // Tanggal selesai
            $table->time('end_time')->nullable(); // Waktu selesai
            $table->string('prayer_end')->nullable(); // Sholat yang belum saat selesai
            $table->timestamps(); // created_at dan updated_at
            $table->string('status')->nullable(); // Menyimpan status haid atau istihadah
        });
    }

    public function down()
    {
        Schema::dropIfExists('menstruations');
        // Schema::table('menstruations', function (Blueprint $table) {
        //     $table->dropColumn(['status', 'is_qada']);
        // });
    }
}

