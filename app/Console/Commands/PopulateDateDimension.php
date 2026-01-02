<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DateDimensionService;

class PopulateDateDimension extends Command
{
    protected $signature = 'dimension:populate-date';
    protected $description = 'Poblar dimensión de fechas (solo una vez)';

    public function handle()
    {
        app(DateDimensionService::class)->populate(false);
        $this->info('Dimensión de fechas poblada correctamente.');
    }
}
