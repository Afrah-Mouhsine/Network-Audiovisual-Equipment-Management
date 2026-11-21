<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutSupport extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "statuts_support";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "nom",
    ];

    /**
     * Get the demandes de support for the status.
     */
    public function demandesSupport()
    {
        return $this->hasMany(DemandeSupport::class, "statut_support_id");
    }
}
