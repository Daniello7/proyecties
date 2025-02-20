<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeyControl extends Model
{
    /** @use HasFactory<\Database\Factories\KeyControlFactory> */
    use HasFactory;

    public function key(): BelongsTo
    {
        return $this->belongsTo(Key::class);
    }
}
