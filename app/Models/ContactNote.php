<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class ContactNote extends Model
{
    use HasUuidKey;

    protected $table   = 'ContactNote';
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

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employeeId');
    }
}
