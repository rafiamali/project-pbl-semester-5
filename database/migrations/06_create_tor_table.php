<?php
// database/migrations/2024_01_01_000006_create_tor_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tor', function (Blueprint $table) {
            $table->id('tor_id');
            $table->string('activity_name', 255);
            $table->text('activity_background');
            $table->text('activity_purpose');
            $table->text('participant');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget_submitted', 15, 2);
            $table->string('pic', 100);
            $table->string('status', 50);
            $table->string('current_stage', 50)->default('draft');
            $table->timestamp('sub_date')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();

            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('budget_id')->nullable();

            $table->foreign('category_id')->references('category_id')->on('activity_category')->onDelete('set null');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('budget_id')->references('budget_id')->on('annual_budget')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tor');
    }
};
