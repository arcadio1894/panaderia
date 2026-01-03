<?php

namespace App\Console\Commands;

use App\TipoCambio;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FillTipoCambioSimple extends Command
{
    protected $signature = 'tipo-cambio:fill-simple';
    protected $description = 'Guarda tipo de cambio diario con valores fijos (demo)';

    public function handle()
    {
        $today = Carbon::now('America/Lima')->toDateString();

        // Si ya existe para hoy, no hacer nada
        if (TipoCambio::where('fecha', $today)->exists()) {
            $this->info("Tipo de cambio ya existe para {$today}");
            return 0;
        }

        // Valores FIJOS para demo
        $precioCompra = 3.70;
        $precioVenta  = 3.75;

        TipoCambio::create([
            'fecha' => $today,
            'precioCompra' => $precioCompra,
            'precioVenta'  => $precioVenta,
        ]);

        $this->info("Tipo de cambio guardado ({$today}) C={$precioCompra} V={$precioVenta}");
        return 0;
    }
}
