<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the journalisations for the user.
     */
    public function journalisations()
    {
        return $this->hasMany(Journalisation::class);
    }

    /**
     * Get the interventions for the user (as technician).
     */
    public function interventions()
    {
        return $this->belongsToMany(Intervention::class, 'intervention_technicien');
    }

    /**
     * Get the mouvements_stock for the user.
     */
    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->role->nom === $role;
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }

    /**
     * Check if user is a technician.
     */
    public function isTechnicien()
    {
        return $this->hasRole('Technicien');
    }

    /**
     * Check if user is a commercial.
     */
    public function isCommercial()
    {
        return $this->hasRole('Commercial');
    }
}
