<?php
// database/migrations/2024_01_01_000009_create_status_hist_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('status_hist', function (Blueprint $table) {
            $table->id('hist_id');
            $table->string('status', 50);
            $table->text('catatan')->nullable();
            $table->timestamp('timestamp_aksi')->useCurrent();
            $table->timestamp('deleted_at')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('tor_id')->nullable();
            $table->unsignedBigInteger('lpj_id')->nullable();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('tor_id')->references('tor_id')->on('tor')->onDelete('cascade');
            $table->foreign('lpj_id')->references('lpj_id')->on('lpj')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('status_hist');
    }
};
