<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PieceJointeCR extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "pieces_jointes_cr";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "compte_rendu_id",
        "type",
        "chemin_fichier",
        "nom_original",
    ];

    /**
     * Get the compte rendu that owns the piece jointe.
     */
    public function compteRendu()
    {
        return $this->belongsTo(CompteRendu::class);
    }
}
