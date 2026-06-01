<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuidKey
{
    public static function bootHasUuidKey(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = str_replace('-', '', Str::uuid()->toString());
            }
        });
    }

    public function getIncrementing(): bool  { return false; }
    public function getKeyType(): string     { return 'string'; }
}
