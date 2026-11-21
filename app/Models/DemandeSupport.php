<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeSupport extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "demandes_support";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "client_id",
        "equipement_id",
        "statut_support_id",
        "user_id", // User who created the request
        "objet",
        "description",
    ];

    /**
     * Get the client that owns the demande de support.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the equipement associated with the demande de support.
     */
    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }

    /**
     * Get the status of the demande de support.
     */
    public function statutSupport()
    {
        return $this->belongsTo(StatutSupport::class);
    }

    /**
     * Get the user who created the demande de support.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
