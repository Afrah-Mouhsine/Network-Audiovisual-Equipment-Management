<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journalisation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "journalisation";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "user_id",
        "action",
        "details",
        "ip_address",
    ];

    /**
     * Get the user that owns the journal entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
