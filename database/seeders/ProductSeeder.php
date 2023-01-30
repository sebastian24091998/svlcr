<?php

namespace Database\Seeders;

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
        //creacion de productos
        Product::create([
            'name' => 'asus vivobook',
            'cost' => 5000,
            'price' =>350,
            'barcode' => '750110065987',
            'stock' => 1000,
            'alerts' => 10,
            'category_id' => 1,
            'image' => 'curso.png'
        ]);

        Product::create([
            'name' => 'hp spectre',
            'cost' => 6000,
            'price' =>1500,
            'barcode' => '77625478521',
            'stock' => 1000,
            'alerts' => 10,
            'category_id' => 2,
            'image' => 'tenis.png'
        ]);
        Product::create([
            'name' => 'hp envy',
            'cost' => 9000,
            'price' =>1400,
            'barcode' => '79632154587',
            'stock' => 1000,
            'alerts' => 10,
            'category_id' => 2,
            'image' => 'iphone11.png'
        ]);
        Product::create([
            'name' => 'hp elitebook',
            'cost' => 7900,
            'price' =>1350,
            'barcode' => '7412589687',
            'stock' => 1000,
            'alerts' => 10,
            'category_id' => 2,
            'image' => 'pcgamer.png'
        ]);
    }
}
