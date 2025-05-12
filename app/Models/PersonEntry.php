<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonEntry extends Model
{
    /** @use HasFactory<\Database\Factories\PersonEntryFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'person_id', 'internal_person_id', 'comment', 'reason', 'arrival_time', 'entry_time', 'exit_time'];

    const REASONS = [
        'Charge',
        'Discharge',
        'Work in Facilities',
        'Cleaning',
        'Urgency',
        'Visit',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function internalPerson(): BelongsTo
    {
        return $this->BelongsTo(InternalPerson::class);
    }

    public function scopeJoinInternalPerson($query, bool $includeExternalPerson = false)
    {
        if ($includeExternalPerson) {
            $query->join('people as person', 'person_entries.person_id', '=', 'person.id');
        }

        return $query
            ->join('internal_people as internalPerson',
                'person_entries.internal_person_id', '=', 'internalPerson.id')
            ->join('people as internalPerson_personRelation',
                'internalPerson.person_id', '=', 'internalPerson_personRelation.id');
    }
}
