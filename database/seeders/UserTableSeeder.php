<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Option;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'admin')->first();
        $role_student  = Role::where('name', 'student')->first();

        $admin = new User();
        $admin->name = 'admin';
        $admin->email = 'admin@admin.com';
        $admin->password = 'admin';
        $admin->save();
        $admin->roles()->attach($role_admin);

        $options = Option::insert([
            ['name' => 'max_words','value' => 100, 'user_id' => $admin->id ],
            ['name' => 'new_percentage', 'value' => 20, 'user_id' => $admin->id ],
            ['name' => 'favourite_percentage', 'value' => 20,'user_id' => $admin->id ],
            ['name' => 'important_percentage', 'value' => 20, 'user_id' => $admin->id ],
            ['name' => 'top_percentage', 'value' => 20, 'user_id' => $admin->id ],
            ['name' => 'low_percentage', 'value' => 20, 'user_id' => $admin->id ]
        ]);

        $student = new User();
        $student->name = 'student';
        $student->email = 'student@student.com';
        $student->password = 'student';
        $student->save();
        $student->roles()->attach($role_student);
        
        $options = Option::insert([
            ['name' => 'max_words','value' => 100, 'user_id' => $student->id ],
            ['name' => 'new_percentage', 'value' => 20, 'user_id' => $student->id ],
            ['name' => 'favourite_percentage', 'value' => 20,'user_id' => $student->id ],
            ['name' => 'important_percentage', 'value' => 20, 'user_id' => $student->id ],
            ['name' => 'top_percentage', 'value' => 20, 'user_id' => $student->id ],
            ['name' => 'low_percentage', 'value' => 20, 'user_id' => $student->id ]
        ]);
    }
}
