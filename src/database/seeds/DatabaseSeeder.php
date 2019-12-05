<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EventsSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(GamesTableSeeder::class);
        $this->call(AppearanceTableSeeder::class);
        $this->call(SliderImagesTableSeeder::class);
    }
}
