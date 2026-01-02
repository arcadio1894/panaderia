<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialVencimiento extends Model
{
    protected $fillable = [
        'material_id',
        'fecha_vencimiento'
    ];

    public function storeMaterial()
    {
        return $this->belongsTo(Material::class);
    }
}
