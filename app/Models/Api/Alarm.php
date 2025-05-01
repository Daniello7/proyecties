<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alarm extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'status', 'description'];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function assignedGuards(): BelongsToMany
    {
        return $this->belongsToMany(Guard::class)
            ->withPivot('date', 'is_false_alarm', 'notes')
            ->withTimestamps();
    }
}
