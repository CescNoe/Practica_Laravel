<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $b1 = new Brand();
        $b1->name = "Toyota";
        $b1->save();

        $b2 = new Brand();
        $b2->name = "Honda";
        $b2->save();

        $b3 = new Brand();
        $b3->name = "Ford";
        $b3->save();

        $b4 = new Brand();
        $b4->name = "Chevrolet";
        $b4->save();

        $b5 = new Brand();
        $b5->name = "Nissan";
        $b5->save();
    }
}
