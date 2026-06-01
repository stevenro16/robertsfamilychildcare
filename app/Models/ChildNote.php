<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class ChildNote extends Model
{
    use HasUuidKey;

    protected $table   = 'ChildNote';
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

    public function child()
    {
        return $this->belongsTo(Child::class, 'childId');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employeeId');
    }
}
