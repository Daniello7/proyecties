<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alarm extends Model
{
    use HasFactory;

    protected $fillable = ['zone_id', 'type', 'is_active', 'description'];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function assignedGuards(): BelongsToMany
    {
        return $this->belongsToMany(Guard::class)
            ->withPivot('id', 'date', 'is_false_alarm', 'notes')
            ->withTimestamps();
    }

    protected static function booted()
    {
        static::addGlobalScope('showTriggers', function (Builder $builder) {
            $showTriggers = request()->query('show_triggers') ?? [];

            if (filter_var($showTriggers, FILTER_VALIDATE_BOOLEAN)) {
                $builder->with('assignedGuards');
            }
        });
    }
}
