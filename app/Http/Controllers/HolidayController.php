<?php

namespace App\Http\Controllers;

use App\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('holiday.index', compact('permissions'));

    }

    public function create()
    {
        $current_date = Carbon::now('America/Lima');
        $current_year = $current_date->year;
        return view('holiday.create', compact('current_year'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $holiday = Holiday::create([
                'description' => $request->get('description'),
                'year' => $request->get('year'),
                'date_complete' => ($request->get('date_complete') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_complete')) : null,
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Feriado guardado con éxito.'], 200);

    }

    public function show(Holiday $holiday)
    {
        //
    }

    public function edit($holiday_id)
    {
        $holiday = Holiday::find($holiday_id);

        return view('holiday.edit', compact('holiday'));

    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $holiday = Holiday::find($request->get('holiday_id'));

            $holiday->description = $request->get('description');
            $holiday->year = $request->get('year');
            $holiday->date_complete = ($request->get('date_complete') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_complete')) : null;

            $holiday->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Feriado modificado con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $holiday = Holiday::find($request->get('holiday_id'));
            $holiday->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Feriado eliminado con éxito.'], 200);

    }

    public function getAllHolidays()
    {
        $holidays = Holiday::select('id', 'description', 'year', 'date_complete')
            ->orderBy('year', 'DESC')
            ->orderBy('date_complete', 'ASC')
            ->get();
        return datatables($holidays)->toJson();

    }

    public function generateHolidays()
    {
        DB::beginTransaction();

        try {
            $tz = 'America/Lima';
            $yearCurrent = Carbon::now($tz)->year;
            $yearNext = $yearCurrent + 1;

            // ¿Qué años ya existen?
            $existsCurrent = Holiday::where('year', $yearCurrent)->exists();
            $existsNext    = Holiday::where('year', $yearNext)->exists();

            // Regla: SOLO permitir tener como máximo 1 año por adelantado
            // Si ya existe el año siguiente, bloquear siempre (hasta que cambie el año actual)
            if ($existsNext) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Lo sentimos, los feriados del próximo año ya fueron creados.'
                ], 422);
            }

            // Caso 1: BD vacía => crear año actual
            $yearMax = Holiday::max('year'); // puede ser null

            if ($yearMax === null) {
                $this->seedFixedHolidaysForYear($yearCurrent, $tz);
                DB::commit();
                return response()->json(['message' => 'Feriados del año actual creados con éxito.'], 200);
            }

            // Caso 2: Existe data pero no existe año actual (ej. base vieja) => crear año actual
            if (!$existsCurrent) {
                $this->seedFixedHolidaysForYear($yearCurrent, $tz);
                DB::commit();
                return response()->json(['message' => 'Feriados del año actual creados con éxito.'], 200);
            }

            // Caso 3: Ya existe año actual y NO existe año siguiente => crear año siguiente copiando del actual
            // (así respetas cambios manuales hechos este año)
            $holidaysCurrent = Holiday::select('description', 'date_complete')
                ->where('year', $yearCurrent)
                ->orderBy('date_complete', 'ASC')
                ->get();

            foreach ($holidaysCurrent as $holiday) {
                $day = $holiday->date_complete->day;
                $month = $holiday->date_complete->month;

                $date = Carbon::createFromDate($yearNext, $month, $day, $tz);

                Holiday::create([
                    'description'   => $holiday->description,
                    'year'          => $yearNext,
                    'date_complete' => $date,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Feriados del próximo año creados con éxito.'], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Crea feriados fijos para el año indicado (desde config).
     */
    private function seedFixedHolidaysForYear($year, $tz)
    {
        $fixed = config('holidays.fixed', array());

        foreach ($fixed as $h) {
            $date = Carbon::createFromDate($year, (int)$h['month'], (int)$h['day'], $tz);

            Holiday::create([
                'description'   => $h['description'],
                'year'          => $year,
                'date_complete' => $date,
            ]);
        }
    }
}
