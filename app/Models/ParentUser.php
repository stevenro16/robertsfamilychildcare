<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ParentUser extends Authenticatable
{
    use HasUuidKey;

    protected $table   = 'ParentUser';
    protected $guarded = [];
    protected $hidden  = ['password', 'remember_token'];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected function casts(): array
    {
        return [
            'mustChangePassword' => 'boolean',
            'createdAt'          => 'datetime',
            'updatedAt'          => 'datetime',
        ];
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }
}
