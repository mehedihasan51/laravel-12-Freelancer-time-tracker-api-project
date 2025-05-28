<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeLogSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('time_logs')->insert([
            [
                'id'                => 1,
                'project_id'        => 1,
                'client_id'         => 1,
                'start_time'        => Carbon::parse('2025-05-23 10:15:59'),
                'end_time'          => Carbon::parse('2025-05-29 12:15:59'),
                'hours'             => 2.00,
                'log_type'          => 'billable',
                'description'       => 'Worked on the initial setup of the project',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'deleted_at'        => null,
            ],
            [
                'id'                => 2,
                'project_id'        => 2,
                'client_id'         => 2,
                'start_time'        => Carbon::parse('2025-05-24 09:00:00'),
                'end_time'          => Carbon::parse('2025-05-30 11:00:00'),
                'hours'             => 3.50,
                'log_type'          => 'non-billable',
                'description'       => 'Conducted a meeting to discuss project requirements',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'deleted_at'        => null,
            ],
            [
                'id'                => 3,
                'project_id'        => 1,
                'client_id'         => 1,
                'start_time'        => Carbon::parse('2025-05-25 14:00:00'),
                'end_time'          => Carbon::parse('2025-05-31 16:00:00'),
                'hours'             => 4.50,
                'log_type'          => 'billable',
                'description'       => 'Developed the main features of the project',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'deleted_at'        => null,
            ],
            [
                'id'                => 4,
                'project_id'        => 2,
                'client_id'         => 2,
                'start_time'        => Carbon::parse('2025-05-26 08:30:00'),
                'end_time'          => Carbon::parse('2025-06-01 10:30:00'),
                'hours'             => 5.00,
                'log_type'          => 'non-billable',
                'description'       => 'Reviewed the project progress and planned next steps',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'deleted_at'        => null,
            ],
            [
                'id'                => 5,
                'project_id'        => 1,
                'client_id'         => 1,
                'start_time'        => Carbon::parse('2025-05-27 13:45:00'),
                'end_time'          => Carbon::parse('2025-06-02 15:45:00'),
                'hours'             => 6.00,
                'log_type'          => 'billable',
                'description'       => 'Fixed bugs and improved the user interface',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'deleted_at'        => null,
            ],
        ]);
    }
}
