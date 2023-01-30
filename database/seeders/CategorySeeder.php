<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // creacion
        Category::create([
            'name' => 'Asus',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'
            
        ]);
        Category::create([
            'name' => 'hp',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'
            
        ]);
        Category::create([
            'name' => 'Dell',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'
            
        ]);
        Category::create([
            'name' => 'toshiba',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'
            
        ]);
    }
}
