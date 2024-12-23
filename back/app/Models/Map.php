<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Map extends Model
{
    use HasFactory;

    public $incrementing = true;
    protected $keyType = 'int';
    protected $table = 'map';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'fk_user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'fk_user_id', 'id');
    }
}