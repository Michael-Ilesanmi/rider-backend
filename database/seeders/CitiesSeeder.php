<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('cities')->truncate();
        $cities = [
            [
                "id"=> 1,
                "city"=> "Ajeromi-Ifelodun"
            ],
            [
                "id"=> 2,
                "city"=> "Alimosho"
            ],
            [
                "id"=> 3,
                "city"=> "Amuwo-Odofin"
            ],
            [
                "id"=> 4,
                "city"=> "Apapa"
            ],
            [
                "id"=> 5,
                "city"=> "Badagry"
            ],
            [
                "id"=> 6,
                "city"=> "Epe"
            ],
            [
                "id"=> 7,
                "city"=> "Eti Osa"
            ],
            [
                "id"=> 8,
                "city"=> "Ibeju-Lekki"
            ],
            [
                "id"=> 9,
                "city"=> "Ifako-Ijaiye"
            ],
            [
                "id"=> 10,
                "city"=> "Ikeja"
            ],
            [
                "id"=> 11,
                "city"=> "Ikorodu"
            ],
            [
                "id"=> 12,
                "city"=> "Kosofe"
            ],
            [
                "id"=> 13,
                "city"=> "Lagos Island"
            ],
            [
                "id"=> 14,
                "city"=> "Lagos Mainland"
            ],
            [
                "id"=> 15,
                "city"=> "Mushin"
            ],
            [
                "id"=> 16,
                "city"=> "Ojo"
            ],
            [
                "id"=> 17,
                "city"=> "Oshodi-Isolo"
            ],
            [
                "id"=> 18,
                "city"=> "Shomolu"
            ],
            [
                "id"=> 19,
                "city"=> "Surulere"
            ]
        ];

        foreach ($cities as $key => $value) {
            # code...
            City::create($value);
        }
    }
}
