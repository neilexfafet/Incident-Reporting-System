<?php

use Illuminate\Database\Seeder;
use App\Rank;

class RankTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        Rank::create([
            'name'=>'Police Officer I',
            'abbreviation'=>'PO1'
        ]);
        Rank::create([
            'name'=>'Police Officer II',
            'abbreviation'=>'PO2'
        ]);
        Rank::create([
            'name'=>'Police Officer III',
            'abbreviation'=>'PO3'
        ]);
        Rank::create([
            'name'=>'Senior Police Officer I',
            'abbreviation'=>'SPO1'
        ]);
        Rank::create([
            'name'=>'Senior Police Officer II',
            'abbreviation'=>'SPO2'
        ]);
        Rank::create([
            'name'=>'Senior Police Officer III',
            'abbreviation'=>'SPO3'
        ]);
        Rank::create([
            'name'=>'Senior Police Officer IV',
            'abbreviation'=>'SPO4'
        ]);
        Rank::create([
            'name'=>'Inspector',
            'abbreviation'=>'INSP'
        ]);
        Rank::create([
            'name'=>'Senior Inspector',
            'abbreviation'=>'S/INSP'
        ]);
        Rank::create([
            'name'=>'Chief Inspector',
            'abbreviation'=>'C/INSP'
        ]);
        Rank::create([
            'name'=>'Superintendent',
            'abbreviation'=>'SUPT'
        ]);
        Rank::create([
            'name'=>'Senior Superintendent',
            'abbreviation'=>'S/SUPT'
        ]);
        Rank::create([
            'name'=>'Chief Superintendent',
            'abbreviation'=>'C/SUPT'
        ]);
        Rank::create([
            'name'=>'Director',
            'abbreviation'=>'DIR'
        ]);
        Rank::create([
            'name'=>'Deputy Director General',
            'abbreviation'=>'DDG'
        ]);
        Rank::create([
            'name'=>'Director General',
            'abbreviation'=>'DGEN'
        ]);
    }
}
