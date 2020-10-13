<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /*$this->call(\App\User::class);
        \App\User::create([
            name => 'Ahmed',
            email => 'ahmed@gmail.com',
            password => 'adminadmin',
        ]);*/
        DB::table('users')->insert([
            'name' => 'ahmed',
            'email' => 'ahmed@gmail.com',
            'password' => Hash::make('adminadmin'),
        ]);
    }
}
