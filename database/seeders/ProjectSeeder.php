<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder {
    public function run(): void {
        DB::table('projects')->insert([
            [
                'id'                => 1,
                'client_id'         => 1,
                'title'             => 'This is new project',
                'description'       => 'This project is very samply',
                'status'            => 'active',
                'deadline'          => Carbon::parse('2025-05-31 00:00:00'),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'deleted_at'        => null,
            ],
            [
                'id'                => 2,
                'client_id'         => 2,
                'title'             => 'This is another project',
                'description'       => 'This project is very complex',
                'status'            => 'active',
                'deadline'          => Carbon::parse('2025-06-30 00:00:00'),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'deleted_at'        => null,
            ],
        ]);
    }
}
