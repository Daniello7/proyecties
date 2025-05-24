<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Key extends Model
{
    /** @use HasFactory<\Database\Factories\KeyFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    protected static function booted(): void
    {
        static::creating(function ($key) {
            $key->area_key_number = static::where('area_id', $key->area_id)
                    ->max('area_key_number') + 1;
        });
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function keyControls(): HasMany
    {
        return $this->hasMany(KeyControl::class);
    }
}
