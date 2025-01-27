<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonEntry extends Model
{
    /** @use HasFactory<\Database\Factories\PersonEntryFactory> */
    use HasFactory;

    protected $fillable = ['person_id', 'entry_time', 'exit_time'];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
