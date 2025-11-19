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
    Schema::create('lampiran', function (Blueprint $table) {
        $table->id('idLampiran');
        $table->unsignedBigInteger('idTor');
        $table->unsignedBigInteger('idLpj')->nullable();
        $table->string('namaFile');
        $table->string('pathFile');
        $table->string('tipeFile');
        $table->integer('ukuranFile');
        $table->timestamp('uploadedAt')->useCurrent();

        $table->foreign('idTor')->references('idTor')->on('tor')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampiran');
    }
};
