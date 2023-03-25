<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plans')->insert([
            [
                'name' => 'Silver',
                'price' => 49.00,
                'currency' => '€',
                'product_id' => 'YOUR_STRIPE_PRODUCT_ID',
                'plan_id' => 'YOUR_STRIPE_PLAN_ID',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Gold',
                'price' => 99.00,
                'currency' => '€',
                'product_id' => 'YOUR_STRIPE_PRODUCT_ID',
                'plan_id' => 'YOUR_STRIPE_PLAN_ID',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Premium',
                'price' => 4.00,
                'currency' => '€',
                'product_id' => 'YOUR_STRIPE_PRODUCT_ID',
                'plan_id' => 'YOUR_STRIPE_PLAN_ID',
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
