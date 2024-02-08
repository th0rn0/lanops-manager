<?php

namespace Database\Seeders;

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
        $this->call(AppearanceTableSeeder::class);
        $this->call(EventsSeeder::class);
        $this->call(SettingsTableSeeder::class);
    }
}
