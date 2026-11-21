<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEquipement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "types_equipement";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "nom",
        "categorie",
    ];

    /**
     * Get the catalogue equipements for the type.
     */
    public function catalogueEquipements()
    {
        return $this->hasMany(CatalogueEquipement::class);
    }
}
