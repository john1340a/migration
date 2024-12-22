<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Map extends Model
{
    use HasFactory, HasUuids;

    /**
     * Indique que l'ID n'est pas auto-incrémenté
     */
    public $incrementing = false;

    /**
     * Le type de la clé primaire
     */
    protected $keyType = 'string';

    /**
     * La table associée au modèle
     */
    protected $table = 'GEOMATIQUEWEB.map';

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'title',          // Changé de 'name' à 'title'
        'fk_user_id'      // Changé de 'user_id' à 'fk_user_id'
    ];

    /**
     * Relation inverse avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'fk_user_id', 'id');  // Spécifiez la clé étrangère
    }

    /**
     * Obtenir le nom de la clé étrangère
     */
    public function getForeignKey()
    {
        return 'fk_user_id';
    }
}