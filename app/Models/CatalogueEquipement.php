<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogueEquipement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "catalogue_equipement";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "type_equipement_id",
        "reference_constructeur_id",
        "modele",
        "description",
        "seuil_alerte",
    ];

    /**
     * Get the type d'équipement that owns the catalogue entry.
     */
    public function typeEquipement()
    {
        return $this->belongsTo(TypeEquipement::class);
    }

    /**
     * Get the constructeur that owns the catalogue entry.
     */
    public function referenceConstructeur()
    {
        return $this->belongsTo(ReferenceConstructeur::class);
    }

    /**
     * Get the equipement instances for the catalogue entry.
     */
    public function equipements()
    {
        return $this->hasMany(Equipement::class);
    }
}
