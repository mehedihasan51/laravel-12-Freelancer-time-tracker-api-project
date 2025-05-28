<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder {
    public function run(): void {
        DB::table('clients')->insert([
            [
                'id'                => 1,
                'user_id'           => 2,
                'name'              => 'Mehedi Hasan',
                'email'             => 'mehediasan45@gmail.com',
                'contact_person'    => '01925811920',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'deleted_at'        => null,
            ],
            [
                'id'                => 2,
                'user_id'           => 2,
                'name'              => 'Limon Hasan',
                'email'             => 'limon@gmail.com',
                'contact_person'    => '01590093438',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'deleted_at'        => null,
            ],
            
        ]);
    }
}
