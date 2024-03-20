<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Modules\Core\Entities\Commune;

class CommuneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Commune::truncate();

        $json = File::get("Modules/Core/Database/Seeders/communes.json");
        $countries = json_decode($json);

        foreach ($countries as $key => $value) {
            Commune::create([
                'nom' => $value->nom,
                'code_postal' => $value->code_postal,
                'wilaya_id' => $value->wilaya_id,
            ]);
        }
    }
}
