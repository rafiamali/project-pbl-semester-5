<?php
// database/seeders/ActivityCategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['category_def' => 'Akademik', 'created_at' => now(), 'updated_at' => now()],
            ['category_def' => 'Kemahasiswaan', 'created_at' => now(), 'updated_at' => now()],
            ['category_def' => 'Penelitian', 'created_at' => now(), 'updated_at' => now()],
            ['category_def' => 'Pengabdian Masyarakat', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('activity_category')->insert($categories);
    }
}
