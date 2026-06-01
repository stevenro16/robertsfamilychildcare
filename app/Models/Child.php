<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasUuidKey;

    protected $table   = 'Child';
    protected $guarded = [];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected function casts(): array
    {
        return [
            'schedule'    => 'array',
            'checkedInAt' => 'datetime',
            'createdAt'   => 'datetime',
            'updatedAt'   => 'datetime',
        ];
    }

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'inquiryId');
    }

    public function notes()
    {
        return $this->hasMany(ChildNote::class, 'childId');
    }

    public function documents()
    {
        return $this->hasMany(ChildDocument::class, 'childId');
    }

    public function childContacts()
    {
        return $this->hasMany(ChildContact::class, 'childId');
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'ChildContact', 'childId', 'contactId')
                    ->withPivot('relationship', 'isPrimary', 'addedByParent', 'id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
