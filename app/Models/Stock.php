<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "nom",
        "adresse",
    ];

    /**
     * Get the equipements currently in this stock location.
     */
    public function equipements()
    {
        return $this->hasMany(Equipement::class);
    }

    /**
     * Get the mouvements de stock originating from this stock.
     */
    public function mouvementsStockOrigine()
    {
        return $this->hasMany(MouvementStock::class, "stock_origine_id");
    }

    /**
     * Get the mouvements de stock destined for this stock.
     */
    public function mouvementsStockDestination()
    {
        return $this->hasMany(MouvementStock::class, "stock_destination_id");
    }
}
