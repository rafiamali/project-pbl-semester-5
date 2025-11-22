<?php
// database/migrations/2024_01_01_000007_create_lpj_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lpj', function (Blueprint $table) {
            $table->id('lpj_id');
            $table->text('activity_result');
            $table->text('activity_evaluation');
            $table->decimal('budget_used', 15, 2);
            $table->string('status', 50);
            $table->string('current_stage', 50)->default('draft');
            $table->timestamp('sub_date')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();

            $table->unsignedBigInteger('tor_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('tor_id')->references('tor_id')->on('tor')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lpj');
    }
};
