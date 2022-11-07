<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('date_types')->insert(
            ['type' => 'term'],
            ['type' => 'holiday'],
        );
        DB::table('date_types')->insert(
            ['type' => 'holiday'],
        );
    }
}
