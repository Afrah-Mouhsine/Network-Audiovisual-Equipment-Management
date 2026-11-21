<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceConstructeur extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "references_constructeur";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "nom",
    ];

    /**
     * Get the catalogue equipements for the constructeur.
     */
    public function catalogueEquipements()
    {
        return $this->hasMany(CatalogueEquipement::class);
    }
}
