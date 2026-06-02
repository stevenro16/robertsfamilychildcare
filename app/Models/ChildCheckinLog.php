<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class ChildCheckinLog extends Model
{
    use HasUuidKey;

    protected $table   = 'ChildCheckinLog';
    protected $guarded = [];

    // We manage occurredAt manually so custom times can be recorded
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'occurredAt' => 'datetime',
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
