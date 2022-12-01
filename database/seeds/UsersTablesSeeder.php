<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
           'name' => 'Timi',
           'context_id' => '1',
           'email' => 'timigab@gmail.com',
           'password' => Hash::make('Emmanuel13'),
           'type' => 'Employee',
           'remember_token' => str_random(10),
        ]);
    }
}
