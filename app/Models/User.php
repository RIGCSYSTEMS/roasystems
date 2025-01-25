<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    // Constantes para los roles
    const ROLE_ADMIN = 'ADMIN';
    const ROLE_DIRECTOR = 'DIRECTOR';
    const ROLE_ABOGADO = 'ABOGADO';
    const ROLE_CLIENTE = 'CLIENTE';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function canManageAcceptedDocuments(): bool
    {
        return in_array($this->role, ['ADMIN', 'DIRECTOR', 'ABOGADO']);
    }

    public function isAdmin()
    {
        return $this->role === 'ADMIN';
    }

    public function isDirector()
    {
        return $this->role === 'DIRECTOR';
    }

    public function isAbogado()
    {
        return $this->role === 'ABOGADO';
    }

    public function isCliente()
    {
        return $this->role === 'CLIENTE';
    }
    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

}
