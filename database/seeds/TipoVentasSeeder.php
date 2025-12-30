<?php

use Illuminate\Database\Seeder;
use \App\TipoVenta;

class TipoVentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoVenta::create([
            'description' => 'Unidad sin Items'
        ]);
        TipoVenta::create([
            'description' => 'Al peso'
        ]);
        TipoVenta::create([
            'description' => 'Itemeable'
        ]);
    }
}
