<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        ## House Cleaning
        \DB::table('games')->delete();

        factory(App\Game::class)->create([
            'name'          => 'Quake',
            'description'   => 'Best game ever',
            'version'       => 'latest',
        ]);
    }
}
