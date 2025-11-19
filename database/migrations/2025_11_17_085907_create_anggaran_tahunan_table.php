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
    Schema::create('anggaran_tahunan', function (Blueprint $table) {
        $table->id('idAnggaranTahunan');
        $table->integer('tahun');
        $table->double('totalPaguAnggaran');
        $table->unsignedBigInteger('idUserInput');
        $table->timestamp('createdAt')->useCurrent();
        $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
        $table->softDeletes('deletedAt');

        $table->foreign('idUserInput')->references('idUser')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggaran_tahunan');
    }
};
