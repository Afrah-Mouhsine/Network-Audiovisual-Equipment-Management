<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes; // Uncomment if using soft deletes

class Client extends Model
{
    use HasFactory; //, SoftDeletes; // Uncomment if using soft deletes

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "nom",
        "secteur",
        "statut",
    ];

    /**
     * Get the contacts for the client.
     */
    public function contactsClient()
    {
        return $this->hasMany(ContactClient::class);
    }

    /**
     * Get the sites for the client.
     */
    public function sitesClient()
    {
        return $this->hasMany(SiteClient::class);
    }

    /**
     * Get the interventions for the client.
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    /**
     * Get the equipements assigned to the client.
     */
    public function equipements()
    {
        return $this->hasMany(Equipement::class);
    }

    /**
     * Get the demandes de support for the client.
     */
    public function demandesSupport()
    {
        return $this->hasMany(DemandeSupport::class);
    }

    /**
     * Get the mouvements de stock originating from the client (returns).
     */
    public function mouvementsStockOrigine()
    {
        return $this->hasMany(MouvementStock::class, "client_origine_id");
    }

    /**
     * Get the mouvements de stock destined for the client (assignments).
     */
    public function mouvementsStockDestination()
    {
        return $this->hasMany(MouvementStock::class, "client_destination_id");
    }
}
