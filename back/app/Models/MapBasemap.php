<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapBasemap extends Model
{
    protected $table = 'map_basemaps';
    public $timestamps = false;
    
    protected $fillable = [
        'map_id',
        'basemap_id',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function map()
    {
        return $this->belongsTo(Map::class, 'map_id');
    }
}