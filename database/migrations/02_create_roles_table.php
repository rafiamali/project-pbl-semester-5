<?php
// database/migrations/2024_01_01_000002_create_roles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('role_def', 50)->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
