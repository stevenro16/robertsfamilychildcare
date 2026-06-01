<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class ChildContact extends Model
{
    use HasUuidKey;

    protected $table   = 'ChildContact';
    protected $guarded = [];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'isPrimary'     => 'boolean',
            'addedByParent' => 'boolean',
            'createdAt'     => 'datetime',
        ];
    }

    public function child()
    {
        return $this->belongsTo(Child::class, 'childId');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }
}
