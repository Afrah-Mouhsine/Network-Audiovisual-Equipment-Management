<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompteRendu extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "comptes_rendus";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "intervention_id",
        "contenu_texte_riche",
        "signature_client_path",
    ];

    /**
     * Get the intervention that owns the compte rendu.
     */
    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    /**
     * Get the pieces jointes for the compte rendu.
     */
    public function piecesJointes()
    {
        return $this->hasMany(PieceJointeCR::class);
    }
}
