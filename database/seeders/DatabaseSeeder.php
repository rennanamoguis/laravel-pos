<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\PaymentMethod;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Item::factory(10)->create();  //Dummy data for items
        // Inventory::factory(30)->create();  //Dummy data for inventories
        // Customer::factory(30)->create(); //Dummy data for customers
        PaymentMethod::factory(3)->create(); 
    }
}
