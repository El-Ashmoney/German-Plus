<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee = new Role();
        $role_employee->name = 'admin';
        $role_employee->description = 'An admin of website';
        $role_employee->save();
        $role_manager = new Role();
        $role_manager->name = 'student';
        $role_manager->description = 'A student of website';
        $role_manager->save();
    }
}
