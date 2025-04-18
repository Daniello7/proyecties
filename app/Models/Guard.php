<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        if ($user->tokenCan('read-own-guard')) {

            $guardId = optional($user->assignedGuard)->id;

            if (!$guardId) return $query;

            return $query->where('id', $guardId);
        }

        return $query;
    }

    public function zones()
    {
        return $this->belongsToMany(Zone::class)
            ->withPivot('schedule')
            ->withTimestamps();
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
