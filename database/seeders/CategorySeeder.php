<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['Gadget', 'Furniture', 'Sneaker'];

        foreach ($names as $name) {
            $slug = str($name)->slug();
            Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'created_by' => optional(getUserWithRole('employee'))->id,
                ]
            );
        }
    }
}
