<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonEntry extends Model
{
    /** @use HasFactory<\Database\Factories\PersonEntryFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'person_id', 'internal_person_id', 'comment_id', 'reason','arrival_time', 'entry_time', 'exit_time'];

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

    public function comment(): BelongsTo
    {
        return $this->BelongsTo(Comment::class);
    }
}
