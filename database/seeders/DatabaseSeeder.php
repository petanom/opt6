<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Orders;
use App\Models\Products;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        Orders::query()->delete();

        Products::query()->delete();

        Orders::factory()->count(1000)->create();

        Products::factory()->count(1000)->create();

        $products = Products::all();

        Orders::all()->each(function ($order) use ($products) {
            $order->products()->attach(
                $products->random(rand(1, 3))->pluck('id')->toArray()
            );
            $summ = 0;
            foreach ($order->products as $p) {
                $qnt = rand(1, 5);
                $summ += $p['price'] * $qnt;
                $order->products()->updateExistingPivot($p['id'], ['qnt' => $qnt]);
            }

            $order->summ = $summ;
            $order->save();
        });
    }
}
