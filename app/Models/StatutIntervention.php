<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutIntervention extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "statuts_intervention";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "nom",
    ];

    /**
     * Get the interventions for the status.
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class, "statut_id");
    }
}
