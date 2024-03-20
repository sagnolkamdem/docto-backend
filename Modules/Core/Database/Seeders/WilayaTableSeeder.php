<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Modules\Core\Entities\Commune;
use Modules\Core\Entities\Wilaya;

class WilayaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Wilaya::truncate();

        $json = File::get("Modules/Core/Database/Seeders/wilayas.json");
        $countries = json_decode($json);

        foreach ($countries as $key => $value) {
            Wilaya::create([
                'nom' => $value->nom,
                'code' => $value->code,
            ]);
        }
    }
}
