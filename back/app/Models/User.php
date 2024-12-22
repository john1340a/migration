<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * La table associée au modèle
     */
    protected $table = 'GEOMATIQUEWEB.user';

    /**
     * Indique si le modèle doit être horodaté
     */
    public $timestamps = false;

    /**
     * Indique que l'ID n'est pas auto-incrémenté
     */
    public $incrementing = false;

    /**
     * Le type de la clé primaire
     */
    protected $keyType = 'string';

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'admin',
        'premium',
        'background_picture'
    ];

    /**
     * Les attributs qui doivent être cachés
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Les attributs qui doivent être castés
     */
    protected $casts = [
        'admin' => 'boolean',
        'premium' => 'boolean',
    ];

    /**
     * Relation avec les cartes
     */
    public function maps()
    {
        return $this->hasMany(Map::class, 'fk_user_id', 'id');
    }
}