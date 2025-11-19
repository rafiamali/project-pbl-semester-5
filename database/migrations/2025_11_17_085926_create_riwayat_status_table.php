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
    Schema::create('riwayat_status', function (Blueprint $table) {
        $table->id('idRiwayat');
        $table->unsignedBigInteger('idTor');
        $table->unsignedBigInteger('idLpj')->nullable();
        $table->unsignedBigInteger('idUserAksi');
        $table->enum('statusBaru', ['draft', 'diajukan', 'disetujui', 'ditolak', 'revisi', 'selesai']);
        $table->text('catatan')->nullable();
        $table->timestamp('timestampAksi')->useCurrent();

        $table->foreign('idTor')->references('idTor')->on('tor')->onDelete('cascade');
        $table->foreign('idUserAksi')->references('idUser')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_status');
    }
};
