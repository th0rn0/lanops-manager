<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class ShopSeeder extends Seeder
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
        \DB::table('shop_items')->truncate();
        \DB::table('shop_item_categories')->truncate();
        \DB::table('shop_item_images')->truncate();

        ## Categories
        factory(App\ShopItemCategory::class, 5)->create([
        ])->each(
            function($category) {
                factory(App\ShopItem::class, random_int(0, 10))->create([
                    'shop_item_category_id' => $category->id,
                ])->each(
                    function($item) {
                        factory(App\ShopItemImage::class, 1)->create([
                            'shop_item_id'  => $item->id,
                            'default'       => 1,
                        ]);
                        factory(App\ShopItemImage::class, random_int(0, 4))->create([
                            'shop_item_id' => $item->id,
                        ]);
                    }
                );
            }
        );
    }
}
