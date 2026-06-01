<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasUuidKey;

    protected $table   = 'Contact';
    protected $guarded = [];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected function casts(): array
    {
        return [
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
        ];
    }

    public function notes()
    {
        return $this->hasMany(ContactNote::class, 'contactId');
    }

    public function childContacts()
    {
        return $this->hasMany(ChildContact::class, 'contactId');
    }

    public function parentUser()
    {
        return $this->hasOne(ParentUser::class, 'contactId');
    }
}
