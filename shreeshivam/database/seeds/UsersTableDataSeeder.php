<?php

use Illuminate\Database\Seeder;

class UsersTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Admin",
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role_id' => '1'
        ]);
	    DB::table('users')->insert([
	        'name' => "Hr",
            'email' => 'hr@gmail.com',
            'password' => bcrypt('hr123'),
            'role_id' => '2'
	    ]);
	    DB::table('users')->insert([
	        'name' => "Accountant",
            'email' => 'accountant@gmail.com',
            'password' => bcrypt('accountant123'),
            'role_id' => '3'
	    ]);
        DB::table('users')->insert([
            'name' => "Sales",
            'email' => 'sales@gmail.com',
            'password' => bcrypt('sales123'),
            'role_id' => '4'
        ]);
    }
}
