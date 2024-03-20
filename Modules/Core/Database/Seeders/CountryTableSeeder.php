<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Entities\Country;
use Modules\Core\Traits\DisableForeignKeys;

class CountryTableSeeder extends Seeder
{
    use DisableForeignKeys;

    protected $countries;

    public function __construct()
    {
        $this->countries = include __DIR__.'/countries.php';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        foreach ($this->countries as $key => $country) {
            Country::query()->create([
                'name' => $country['name']['common'],
                'name_official' => $country['name']['official'],
                'cca2' => $country['cca2'],
                'cca3' => $country['cca3'],
                'flag' => $country['flag'],
                'latitude' => $country['latlng'][0],
                'longitude' => $country['latlng'][1],
                'currencies' => $country['currencies'],
                'callingCodes' => $country['callingCodes']
            ]);
        }

        $this->enableForeignKeys();
    }
}
