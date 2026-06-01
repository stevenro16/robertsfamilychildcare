<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class ChildDocument extends Model
{
    use HasUuidKey;

    protected $table      = 'ChildDocument';
    protected $guarded    = [];
    public    $timestamps = false;

    protected function casts(): array
    {
        return [
            'uploadedByParent' => 'boolean',
            'size'             => 'integer',
            'uploadedAt'       => 'datetime',
        ];
    }

    public function child()
    {
        return $this->belongsTo(Child::class, 'childId');
    }
}
