<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Entities\Currency;
use Modules\Core\Traits\DisableForeignKeys;

class CurrencyTableSeeder extends Seeder
{
    use DisableForeignKeys;

    protected $currencies;

    public function __construct()
    {
        $this->currencies = include __DIR__.'/currencies.php';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        foreach ($this->currencies as $code => $currency) {
            $data = array_merge($currency, ['code' => $code]);
            Currency::query()->create($data);
        }

        $this->enableForeignKeys();
    }
}
