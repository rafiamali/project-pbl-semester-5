<?php
// database/migrations/2024_01_01_000005_create_annual_budget_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('annual_budget', function (Blueprint $table) {
            $table->id('budget_id');
            $table->string('tahun', 4);
            $table->decimal('budget', 15, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('annual_budget');
    }
};
