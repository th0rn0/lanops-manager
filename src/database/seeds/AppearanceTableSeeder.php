<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class AppearanceTableSeeder extends Seeder
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
        \DB::table('appearance')->delete();

        factory(App\Appearance::class)->create([
            'key'   => 'color_primary',
            'value' => 'orange',
            'type'  => 'CSS_VAR',
        ]);

        factory(App\Appearance::class)->create([
            'key'   => 'color_primary_text',
            'value' => 'white',
            'type'  => 'CSS_VAR',
        ]);

        factory(App\Appearance::class)->create([
            'key'   => 'color_secondary',
            'value' => '#333',
            'type'  => 'CSS_VAR',
        ]);

        factory(App\Appearance::class)->create([
            'key'   => 'color_secondary_text',
            'value' => '#333',
            'type'  => 'CSS_VAR',
        ]);

        factory(App\Appearance::class)->create([
            'key'   => 'color_links',
            'value' => 'blue',
            'type'  => 'CSS_VAR',
        ]);

        factory(App\Appearance::class)->create([
            'key'   => 'color_background',
            'value' => '#fff',
            'type'  => 'CSS_VAR',
        ]);

        factory(App\Appearance::class)->create([
            'key'   => 'color_text',
            'value' => '#333',
            'type'  => 'CSS_VAR',
        ]);

        factory(App\Appearance::class)->create([
            'key'   => 'color_header_background',
            'value' => '#333',
            'type'  => 'CSS_VAR',
        ]);

        factory(App\Appearance::class)->create([
            'key'   => 'color_header_text',
            'value' => '#9d9d9d',
            'type'  => 'CSS_VAR',
        ]);

    }
}
