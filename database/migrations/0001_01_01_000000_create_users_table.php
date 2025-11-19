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
    Schema::create('users', function (Blueprint $table) {
        $table->id('idUser');
        $table->string('ssoId')->unique();
        $table->string('namaLengkap');
        $table->string('email')->unique();
        $table->enum('role', ['admin', 'kepala_departemen', 'staff', 'pimpinan'])->default('staff');
        $table->timestamp('createdAt')->useCurrent();
        $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
        $table->softDeletes('deletedAt');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
