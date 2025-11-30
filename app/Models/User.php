<?php

namespace App\Models;


use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


/**
 * @property mixed $role
 * @property mixed $status
 * @property mixed $username
 * @property mixed $primary_phone
 * @property mixed $email
 * @property mixed $secondary_phone
 * @property mixed $password
 * @method static where(string $string, string $username)
 * @method static find(int $id)
 * @method static orderBy(string $string, string $string1)
 */
class User extends Authenticatable
{

    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'username',
        'email',
        'password',
        'primary_phone',
        'secondary_phone',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}