<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasUuidKey;

    protected $table   = 'Message';
    protected $guarded = [];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'readAt'    => 'datetime',
            'createdAt' => 'datetime',
        ];
    }

    public function parentUser()
    {
        return $this->belongsTo(ParentUser::class, 'parentUserId');
    }
}
