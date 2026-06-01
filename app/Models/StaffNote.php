<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class StaffNote extends Model
{
    use HasUuidKey;

    protected $table   = 'StaffNote';
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

    public function staffMember()
    {
        return $this->belongsTo(StaffMember::class, 'staffMemberId');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employeeId');
    }
}
