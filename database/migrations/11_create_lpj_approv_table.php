<?php
// database/migrations/2024_01_01_000011_create_lpj_approv_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lpj_approv', function (Blueprint $table) {
            $table->id('approv_id');
            $table->unsignedBigInteger('lpj_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('status', 20);
            $table->text('catatan')->nullable();
            $table->string('action', 20);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('lpj_id')->references('lpj_id')->on('lpj')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lpj_approv');
    }
};
