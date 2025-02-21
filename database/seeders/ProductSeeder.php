<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // ID をリセットする
        DB::table('products')->truncate();

        // サンプルデータを挿入
        Product::create([
            'company_id' => 1,
            'img_path' => 'testA',
            'product_name' => '商品A',
            'price' => 1000, // 価格
            'stock' => 50, // 在庫数
        ]);

        Product::create([
            'company_id' => 2,
            'img_path' => 'testB',
            'product_name' => '商品B',
            'price' => 1500, // 価格
            'stock' => 30, // 在庫数
        ]);

        Product::create([
            'company_id' => 3,
            'img_path' => 'testC',
            'product_name' => '商品C',
            'price' => 2000, // 価格
            'stock' => 20, // 在庫数
        ]);
    }
}
