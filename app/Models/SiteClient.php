<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteClient extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "sites_client";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "client_id",
        "nom_site",
        "adresse",
        "ville",
        "code_postal",
        "pays",
    ];

    /**
     * Get the client that owns the site.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the interventions for the site.
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class, "site_id");
    }

    /**
     * Get the equipements assigned to the site.
     */
    public function equipements()
    {
        return $this->hasMany(Equipement::class, "site_id");
    }

    /**
     * Get the mouvements de stock destined for the site (assignments).
     */
    public function mouvementsStockDestination()
    {
        return $this->hasMany(MouvementStock::class, "site_destination_id");
    }
}
