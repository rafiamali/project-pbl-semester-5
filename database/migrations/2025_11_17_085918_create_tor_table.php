<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('tor', function (Blueprint $table) {
        $table->id('idTor');
        $table->unsignedBigInteger('idPengguna');
        $table->unsignedBigInteger('idKategori');
        $table->string('judulKegiatan');
        $table->text('tujuanKegiatan');
        $table->date('jadwalMulai');
        $table->date('jadwalSelesai');
        $table->double('jumlahAnggaranDiajukan');
        $table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak', 'revisi'])->default('draft');
        $table->date('tanggalPengajuan')->nullable();
        $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
        $table->softDeletes('deletedAt');

        $table->foreign('idPengguna')->references('idUser')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tor');
    }
};
