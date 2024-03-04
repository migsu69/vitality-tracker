<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample data into the 'employees' table
        /* DB::table('employees')->insert([
            [
                'employee_id' => '1004',
                'last_name' => 'Doe',
                'first_name' => 'John',
                'middle_name' => 'A',
            ],

        ]); */

        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create roles
        Role::create(['user_role' => 'Super Admin']);
        Role::create(['user_role' => 'Employee']);

        // Create permissions
        Permission::create(['name' => 'export']);

        // Assign permissions to roles
        Role::findByName('Super Admin')->givePermissionTo('export');
    }
}
