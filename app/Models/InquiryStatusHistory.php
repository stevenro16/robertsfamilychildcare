<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class InquiryStatusHistory extends Model
{
    use HasUuidKey;

    protected $table   = 'InquiryStatusHistory';
    protected $guarded = [];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'createdAt' => 'datetime',
        ];
    }

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'inquiryId');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employeeId');
    }
}
