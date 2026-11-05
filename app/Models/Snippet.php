<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Snippet extends Model
{
    use Prunable;

    protected $fillable = [
        'title',
        'slug',
        'code',
        'language',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function prunable(): Builder
    {
        return static::where('expires_at', '<=', now());
    }
}
