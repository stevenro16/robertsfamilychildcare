<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class TestimonialLink extends Model
{
    use HasUuidKey;

    protected $table   = 'TestimonialLink';
    protected $guarded = [];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'usedAt'    => 'datetime',
            'createdAt' => 'datetime',
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'createdById');
    }

    public function testimonial()
    {
        return $this->hasOne(Testimonial::class, 'linkId');
    }
}
