<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "client_id",
        "site_id",
        "priorite_id",
        "statut_id",
        "objet",
        "description",
        "date_planification",
        "date_cloture",
        "compte_rendu_interne",
        "temps_passe",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "date_planification" => "datetime",
        "date_cloture" => "datetime",
    ];

    /**
     * Get the client that owns the intervention.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the site where the intervention takes place.
     */
    public function siteClient()
    {
        // Use the correct foreign key name if different
        return $this->belongsTo(SiteClient::class, "site_id");
    }

    /**
     * Get the priority of the intervention.
     */
    public function prioriteIntervention()
    {
        return $this->belongsTo(PrioriteIntervention::class, "priorite_id");
    }

    /**
     * Get the status of the intervention.
     */
    public function statutIntervention()
    {
        return $this->belongsTo(StatutIntervention::class, "statut_id");
    }

    /**
     * Get the technicians assigned to the intervention.
     */
    public function techniciens()
    {
        // Assuming the default users table is named "users"
        return $this->belongsToMany(User::class, "intervention_technicien");
    }

    /**
     * Get the equipements involved in the intervention.
     */
    public function equipements()
    {
        return $this->belongsToMany(Equipement::class, "intervention_equipement");
    }

    /**
     * Get the compte rendu for the intervention.
     */
    public function compteRendu()
    {
        // Assuming one compte rendu per intervention
        return $this->hasOne(CompteRendu::class);
    }

    /**
     * Get the mouvements de stock related to the intervention.
     */
    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }
}
