<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalPerson extends Person
{
    /** @use HasFactory<\Database\Factories\InternalPersonFactory> */
    use HasFactory;

    protected $fillable = [
        'person_id',
        'email',
        'contract_type',
        'hired_at'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
