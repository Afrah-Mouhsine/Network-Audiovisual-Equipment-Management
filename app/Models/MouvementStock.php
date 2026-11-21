<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "mouvements_stock";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "equipement_id",
        "stock_origine_id",
        "stock_destination_id",
        "client_origine_id",
        "client_destination_id",
        "site_destination_id",
        "type",
        "user_id",
        "intervention_id",
        "commentaire",
    ];

    /**
     * Get the equipement associated with the movement.
     */
    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }

    /**
     * Get the origin stock location.
     */
    public function stockOrigine()
    {
        return $this->belongsTo(Stock::class, "stock_origine_id");
    }

    /**
     * Get the destination stock location.
     */
    public function stockDestination()
    {
        return $this->belongsTo(Stock::class, "stock_destination_id");
    }

    /**
     * Get the origin client.
     */
    public function clientOrigine()
    {
        return $this->belongsTo(Client::class, "client_origine_id");
    }

    /**
     * Get the destination client.
     */
    public function clientDestination()
    {
        return $this->belongsTo(Client::class, "client_destination_id");
    }

    /**
     * Get the destination site.
     */
    public function siteDestination()
    {
        return $this->belongsTo(SiteClient::class, "site_destination_id");
    }

    /**
     * Get the user who performed the movement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the intervention related to the movement.
     */
    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }
}
