<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DemoReset extends Command
{
    protected $signature = 'demo:reset {--seed : Ejecuta db:seed al final} {--keep-users : No borra users}';
    protected $description = 'Resetea data demo sin migraciones: trunca todo excepto tablas protegidas y opcionalmente ejecuta db:seed';

    public function handle()
    {
        $dbName = DB::getDatabaseName();

        // Tablas que NO se deben tocar
        $protected = array(
            'migrations',

            // Auth + Spatie
            'users',
            'password_resets',
            'roles',
            'permissions',
            'role_has_permissions',
            'model_has_roles',
            'model_has_permissions',

            // Config / catálogos base
            'data_generals',
            'material_detail_settings',

            'areas',
            'categories',
            'subcategories',
            'material_types',
            'subtypes',
            'unit_measures',
            'brands',
            'examplers',
            'qualities',
            'warrants',
            'typescraps',
            'positions',
            'warehouses',
            'shelves',
            'levels',
            'containers',
            'locations',

            'payment_deadlines',
            'discount_quantities',
            'tipo_ventas',
            'bills',
            'banks',
            'civil_statuses',
            'pension_systems',
            'work_functions',
            'working_days',
            'phases',
            'type_taxes',
            'tipo_cambios',
            'tipo_pagos',
            'reason_transfers',
            'reason_suspensions',

            // Infra
            'failed_jobs',

            'materials'
        );

        $keepUsers = (bool) $this->option('keep-users');

        $this->info('BD: ' . $dbName);

        // Traer solo tablas reales (excluye VIEWS)
        $rows = DB::select(
            "SELECT table_name
             FROM information_schema.tables
             WHERE table_schema = ?
               AND table_type = 'BASE TABLE'",
            array($dbName)
        );

        $allTables = array();
        foreach ($rows as $r) {
            $allTables[] = $r->table_name;
        }

        $toTruncate = array();
        foreach ($allTables as $t) {
            if (!in_array($t, $protected, true)) {
                $toTruncate[] = $t;
            }
        }

        $this->line('Tablas totales: ' . count($allTables));
        $this->line('A truncar: ' . count($toTruncate));

        DB::beginTransaction();

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Borrar usuarios de prueba, pero mantener admin
            if (!$keepUsers) {
                $adminEmail = 'admin@venti360.com';

                $admin = DB::table('users')->select('id')->where('email', $adminEmail)->first();

                if ($admin) {
                    $adminId = (int) $admin->id;

                    DB::table('model_has_roles')
                        ->where('model_type', 'App\\User')
                        ->where('model_id', '!=', $adminId)
                        ->delete();

                    DB::table('model_has_permissions')
                        ->where('model_type', 'App\\User')
                        ->where('model_id', '!=', $adminId)
                        ->delete();

                    DB::table('users')->where('id', '!=', $adminId)->delete();

                } else {
                    $this->warn('No se encontró admin@venti360.com. No se borraron users por seguridad.');
                }
            }

            // Truncar data de pruebas
            foreach ($toTruncate as $table) {
                DB::table($table)->truncate();
                $this->line('Truncate OK: ' . $table);
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->error('Error en reset: ' . $e->getMessage());
            return 1;
        }

        // Re-seed para dejar data base
        if ($this->option('seed')) {
            $this->info('Ejecutando db:seed --force ...');
            Artisan::call('db:seed', array('--force' => true));
            $this->line(Artisan::output());
        }

        // Limpieza caches
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        $this->info('Reset demo terminado ✅');
        return 0;
    }
}
