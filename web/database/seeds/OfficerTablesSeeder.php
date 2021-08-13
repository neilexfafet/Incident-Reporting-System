<?php

use Illuminate\Database\Seeder;
use App\Officer;

class OfficerTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Officer::create([
            'first_name'    => ' ',
            'middle_name'    => ' ',
            'last_name'    => 'DEVELOPER',
            'rank'    => 'DEVELOPER',
            'id_no' => 'DEVELOPER',
            'badge_no' => ' ',
            'email'    => 'developer@gmail.com',
            'birthday'    => '2000/01/01',
            'gender'    => ' ',
            'address'    => ' ',
            'contact_no'    => ' ',
            'image'    => 'TBD',
            'is_active' => '1',
            'status' => 'admin',
        ]);
    }
}
