<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VitalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample data into the 'vitals' table
        DB::table('vitals')->insert([
            [
                'employee_id' => 001, // Replace with the actual employee_id from your 'employees' table
                'month' => 'January',
                'year' => 2023,
                'pulse_rate' => 80,
                'body_temperature' => 98.6,
                'respiratory_rate' => 16,
                'bp' => '120/80',
                'bmi' => 25.5,
            ],
            [
                'employee_id' => 002, // Replace with another employee_id
                'month' => 'January',
                'year' => 2023,
                'pulse_rate' => 75,
                'body_temperature' => 98.2,
                'respiratory_rate' => 18,
                'bp' => '130/85',
                'bmi' => 23.8,
            ],
            // Add more sample data as needed
        ]);
    }
}
