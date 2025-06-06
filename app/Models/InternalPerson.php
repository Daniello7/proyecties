<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalPerson extends Person
{
    /** @use HasFactory<\Database\Factories\InternalPersonFactory> */
    use HasFactory;

    protected $table = 'internal_people';
    protected $fillable = [
        'person_id',
        'email',
        'contract_type',
        'hired_at',
        'phone',
        'address',
        'country',
        'city',
        'province',
        'zip_code'
    ];


    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
