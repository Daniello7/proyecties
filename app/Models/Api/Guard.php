<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guard extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'dni'];

    public function scopeName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    public function scopeDni($query, $dni)
    {
        return $query->where('dni', 'like', "%{$dni}%");
    }

    public function scopeOwnGuard($query)
    {
        $user = auth()->user();
        $guardId = $user->assignedGuard?->id;

        if (!$user->tokenCan('read-own-guard') || !$guardId) return $query;

        return $query->where('id', $guardId);
    }

    public function zones()
    {
        return $this->belongsToMany(Zone::class)
            ->withPivot('schedule')
            ->withTimestamps();
    }

    public function reports(): HasMany
    {
        return $this->hasMany(GuardReport::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('withZones', function (Builder $builder) {
            $withZones = request()->query('with_zones');

            if (filter_var($withZones, FILTER_VALIDATE_BOOLEAN)) {
                $builder->with('zones');
            }
        });
    }
}
