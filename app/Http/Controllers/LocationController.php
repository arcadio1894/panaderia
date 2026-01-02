<?php

namespace App\Http\Controllers;

use App\DataGeneral;
use App\Item;
use App\Location;
use App\Material;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::with(['area', 'warehouse', 'shelf', 'level', 'container', 'position'])
            ->get();

        return view('inventory.locations', compact('locations'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Location $location)
    {
        //
    }

    public function edit(Location $location)
    {
        //
    }

    public function update(Request $request, Location $location)
    {
        //
    }

    public function destroy(Location $location)
    {
        //
    }

    public function getLocations()
    {
        $locations = Location::with(['area', 'warehouse', 'shelf', 'level', 'container', 'position'])->get();

        //dd(datatables($materials)->toJson());
        return datatables($locations)->toJson();
    }

    public function getJsonLocations()
    {
        $data = DataGeneral::where('name', 'idWarehouseTienda')->first();
        $excludedWarehouseId = $data->valueNumber;
        $array = [];
        $locations = Location::with(['area', 'warehouse', 'shelf', 'level', 'container', 'position'])
            ->whereHas('warehouse', function ($query) use ($excludedWarehouseId) {
                $query->where('id', '!=', $excludedWarehouseId);
            })
            ->where('default', true)
            ->get();

        foreach ($locations as $location) {
            //$l = 'AR:' . $location->area->name . '|AL:' . $location->warehouse->name . '|AN:' . $location->shelf->name . '|NIV:' . $location->level->name . '|CON:' . $location->container->name . '|POS:' . $location->position->name;
            $l = $location->description;
            $array[] = [
                'id' => $location->id,
                'location' => $l
            ];
        }

        return $array;
    }

    public function getItemsLocation($id)
    {
        $items = Item::where('location_id', $id)->whereIn('state_item', ['entered', 'scraped'])->with('material')->with('typescrap')->with('detailEntry')->get();

        //dd(datatables($items)->toJson());
        return datatables($items)->toJson();

    }

    public function getMaterialsByLocation($id)
    {
        $location = Location::with(['area', 'warehouse', 'shelf', 'level', 'container', 'position'])->find($id);
        return view('inventory.materials', compact('location'));
    }

    public function getMaterialsLocation($id)
    {
        //$begin = microtime(true);
        $items = Item::select(['material_id', 'percentage'])->where('location_id', $id)->where('state_item', '<>', 'exited')->get();

        $array_quantity = [];
        $materials_quantity = [];

        foreach ( $items as $item )
        {
            array_push($array_quantity,
                array(
                    'material_id'=>$item->material_id,
                    //'description' => $item->material->full_description,
                    'quantity'=> (float)$item->percentage));

        }

        $new_arr = array();
        foreach($array_quantity as $item) {
            if(isset($new_arr[$item['material_id']])) {
                $new_arr[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr[$item['material_id']] = $item;
        }

        $materials_quantity = array_values($new_arr);

        $materials = [];

        for( $i=0; $i<count($materials_quantity); $i++ )
        {
            $material = Material::where('enable_status', 1)
                ->find($materials_quantity[$i]['material_id']);
            array_push($materials,
                array(
                    'code' => $material->code,
                    'material_id' => $materials_quantity[$i]['material_id'],
                    'description' => $material->full_description,
                    'quantity'=> $materials_quantity[$i]['quantity']));
        }
        //$end = microtime(true) - $begin;
        //dump($materials_quantity);
        //dump($end. ' segundos');

        //dd();
        //dd(datatables($items)->toJson());
        return datatables($materials)->toJson();

    }

    public function viewItemsMaterialLocation($material_id, $location_id)
    {
        $location = Location::with(['area', 'warehouse', 'shelf', 'level', 'container', 'position'])->find($location_id);
        $material = Material::find($material_id);
        return view('inventory.items', compact('location', 'material'));
    }

    public function getItemsMaterialLocation($id_material, $id_location)
    {
        //$begin = microtime(true);
        $items = Item::select([
            'material_id',
            'code',
            'length',
            'width',
            'percentage',
            'state_item'
        ])
            ->where('location_id', $id_location)
            ->where('material_id', $id_material)
            ->where('state_item', '<>', 'exited')->get();

        $arrayItems = [];

        /*foreach( $items as $item )
        {
            $material = Material::where('enable_status', 1)
                ->find($item->material_id);
            array_push($arrayItems,
                array(
                    'item' => $item->code,
                    'material_id' => $item->material_id,
                    'length' => $item->length,
                    'width' => $item->width,
                    'percentage' => $item->percentage,
                    'state_item' => $item->state_item,
                )
            );
        }*/
        //$end = microtime(true) - $begin;

        return datatables($items)->toJson();

    }
}
