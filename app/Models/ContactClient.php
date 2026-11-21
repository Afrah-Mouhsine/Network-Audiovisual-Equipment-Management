<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactClient extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "contacts_client";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "client_id",
        "nom",
        "role",
        "email",
        "telephone",
    ];

    /**
     * Get the client that owns the contact.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
