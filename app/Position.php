<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'name',
        'comment',
        'container_id',
        'status'
    ];

    public function container()
    {
        return $this->belongsTo('App\Container');
    }

    public function locations()
    {
        return $this->hasMany('App\Location');
    }
}
