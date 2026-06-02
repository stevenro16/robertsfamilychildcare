<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class StaffMember extends Model
{
    use HasUuidKey;

    protected $table   = 'StaffMember';
    protected $guarded = [];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'sortOrder'       => 'integer',
            'yearsExperience' => 'integer',
            'startDate'       => 'date',
            'isActive'        => 'boolean',
            'createdAt'       => 'datetime',
        ];
    }

    public function notes()
    {
        return $this->hasMany(StaffNote::class, 'staffMemberId');
    }
}
