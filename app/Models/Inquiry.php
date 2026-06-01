<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasUuidKey;

    protected $table   = 'Inquiry';
    protected $guarded = [];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected function casts(): array
    {
        return [
            'isSnoozed'   => 'boolean',
            'snoozeUntil' => 'datetime',
            'createdAt'   => 'datetime',
            'updatedAt'   => 'datetime',
        ];
    }

    public function notes()
    {
        return $this->hasMany(InquiryNote::class, 'inquiryId')->orderBy('createdAt');
    }

    public function statusHistory()
    {
        return $this->hasMany(InquiryStatusHistory::class, 'inquiryId')->orderBy('createdAt');
    }

    public function child()
    {
        return $this->hasOne(Child::class, 'inquiryId');
    }
}
