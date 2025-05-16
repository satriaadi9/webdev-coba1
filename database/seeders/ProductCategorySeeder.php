<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('product_categories')->insert([
            [
                'name'=>'Electronics',
                'description'=>'Device and gadgets',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name'=>'Clothing',
                'description'=>'Men and women apparel',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name'=>'Books',
                'description'=>'Fiction and nonfic book',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
        ]);
    }
}
