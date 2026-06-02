<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasUuidKey;

    protected $table   = 'GalleryImage';
    protected $guarded = [];
    protected $hidden  = ['data'];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'sortOrder' => 'integer',
            'takenAt'   => 'date',
            'createdAt' => 'datetime',
        ];
    }
}
