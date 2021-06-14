<?php

namespace Database\Seeders;

use App\Models\Airport;
use App\Models\Flight;
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
        // \App\Models\User::factory(10)->create();
        $airports = config('airports.mock');
        
        Airport::insert($airports);

        $airports_codes = collect($airports)->pluck('code');


        for($i = 0; $i < 200; $i++) {
            $temp = $airports_codes->toArray();
            shuffle( $temp );
            Flight::factory(1)->create(['code_departure' => array_pop($temp), 'code_arrival' => array_pop($temp)]);
        }

    }
}
