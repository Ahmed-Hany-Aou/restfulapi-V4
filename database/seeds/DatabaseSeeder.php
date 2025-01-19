<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // Truncate all tables to start fresh
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();

        // Define the quantity of data to seed
        $usersQuantity = 1000;
        $categoriesQuantity = 30;
        $productsQuantity = 1000;

        // Seed users
        factory(User::class, $usersQuantity)->create();

        // Seed categories
        factory(Category::class, $categoriesQuantity)->create();

        // Seed products and link them to random categories
        factory(Product::class, $productsQuantity)->create()->each(function ($product) {
            $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
            $product->categories()->attach($categories);
        });

        // Seed transactions and associate them with random buyers and products
        $buyers = User::inRandomOrder()->take(500)->get(); // Select 500 random users as buyers

        foreach ($buyers as $buyer) {
            factory(Transaction::class, 2)->create([ // Create 2 transactions per buyer
                'buyer_id' => $buyer->id,
                'product_id' => Product::all()->random()->id, // Assign random product
            ]);
        }
    }
}
