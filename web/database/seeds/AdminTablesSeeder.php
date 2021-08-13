<?php

use Illuminate\Database\Seeder;
use App\Admin;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'username'    => 'developer',
            'password'   =>  Hash::make('password'),
            'admin_name' => 'DEVELOPER',
            'is_active' => '1',
        ]);
    }
}
