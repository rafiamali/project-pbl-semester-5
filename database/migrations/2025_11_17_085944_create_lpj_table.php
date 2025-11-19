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
    Schema::create('lpj', function (Blueprint $table) {
        $table->id('idLpj');
        $table->unsignedBigInteger('idTor');
        $table->text('hasilKegiatan');
        $table->text('evaluasiKegiatan');
        $table->double('jumlahAnggaranRealisasi');
        $table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak', 'revisi'])->default('draft');
        $table->date('tanggalPengajuan')->nullable();
        $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
        $table->softDeletes('deletedAt');

        $table->foreign('idTor')->references('idTor')->on('tor')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lpj');
    }
};
