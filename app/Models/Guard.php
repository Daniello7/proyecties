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
        return $query->where('dni', $dni);
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
            if (request()->has('with_zones')) {
                $builder->with('zones');
            }
        });
    }
}
