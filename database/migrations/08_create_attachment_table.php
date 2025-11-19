<?php
// database/migrations/2024_01_01_000008_create_attachment_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attachment', function (Blueprint $table) {
            $table->id('attach_id');
            $table->string('file_path', 500);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();

            $table->unsignedBigInteger('tor_id')->nullable();
            $table->unsignedBigInteger('lpj_id')->nullable();

            $table->foreign('tor_id')->references('tor_id')->on('tor')->onDelete('cascade');
            $table->foreign('lpj_id')->references('lpj_id')->on('lpj')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attachment');
    }
};
