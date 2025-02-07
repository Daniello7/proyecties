<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    /** @use HasFactory<\Database\Factories\PersonFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'document_number',
        'company',
        'comment'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function comment(): HasOne
    {
        return $this->hasOne(Comment::class);
    }

    public function internalPerson(): HasOne
    {
        return $this->hasOne(InternalPerson::class);
    }

    public function personEntries(): HasMany
    {
        return $this->hasMany(PersonEntry::class);
    }
}
