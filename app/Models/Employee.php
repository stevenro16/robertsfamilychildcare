<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasUuidKey;

    protected $table      = 'Employee';
    protected $guarded    = [];
    protected $hidden     = ['password', 'remember_token'];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'mustChangePassword' => 'boolean',
            'isActive'           => 'boolean',
            'createdAt'          => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }
}
