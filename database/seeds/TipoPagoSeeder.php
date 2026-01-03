<?php

use Illuminate\Database\Seeder;
use App\TipoPago;

class TipoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoPago::create([
            'description' => 'YAPE',
            'vuelto' => 0
        ]);
        TipoPago::create([
            'description' => 'PLIN',
            'vuelto' => 0
        ]);
        TipoPago::create([
            'description' => 'POS',
            'vuelto' => 0
        ]);
        TipoPago::create([
            'description' => 'EN EFECTIVO',
            'vuelto' => 1
        ]);
    }
}
