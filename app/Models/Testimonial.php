<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasUuidKey;

    protected $table   = 'Testimonial';
    protected $guarded = [];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'rating'     => 'integer',
            'isActive'   => 'boolean',
            'createdAt'  => 'datetime',
            'reviewedAt' => 'datetime',
        ];
    }

    public function link()
    {
        return $this->belongsTo(TestimonialLink::class, 'linkId');
    }
}
