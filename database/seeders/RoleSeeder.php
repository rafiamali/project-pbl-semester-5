<?php
// database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['role_def' => 'mahasiswa'],
            ['role_def' => 'dosen'],
            ['role_def' => 'sekretaris jurusan'],
            ['role_def' => 'admin jurusan'],
            ['role_def' => 'ketua jurusan'],
        ];

        DB::table('roles')->insert($roles);
    }
}
