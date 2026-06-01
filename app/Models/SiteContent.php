<?php

namespace App\Models;

use App\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    use HasUuidKey;

    protected $table      = 'SiteContent';
    protected $guarded    = [];
    public    $timestamps = false;

    protected function casts(): array
    {
        return [
            'updatedAt' => 'datetime',
        ];
    }

    public static function get(string $key, string $default = ''): string
    {
        $row = static::where('key', $key)->first();
        return $row ? ($row->value ?? $default) : $default;
    }

    public static function set(string $key, string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'updatedAt' => now()]);
    }
}
