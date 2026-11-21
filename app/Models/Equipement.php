<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "catalogue_equipement_id",
        "numero_serie",
        "firmware",
        "date_garantie",
        "stock_id",
        "client_id",
        "site_id",
        "statut",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "date_garantie" => "date",
    ];

    /**
     * Get the catalogue entry that owns the equipement.
     */
    public function catalogueEquipement()
    {
        return $this->belongsTo(CatalogueEquipement::class);
    }

    /**
     * Get the stock location that owns the equipement.
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Get the client that owns the equipement.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the site that owns the equipement.
     */
    public function siteClient()
    {
        // Use the correct foreign key name if different
        return $this->belongsTo(SiteClient::class, "site_id");
    }

    /**
     * Get the mouvements de stock for the equipement.
     */
    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }

    /**
     * Get the interventions associated with the equipement.
     */
    public function interventions()
    {
        return $this->belongsToMany(Intervention::class, "intervention_equipement");
    }

    /**
     * Get the demandes de support for the equipement.
     */
    public function demandesSupport()
    {
        return $this->hasMany(DemandeSupport::class);
    }
}
