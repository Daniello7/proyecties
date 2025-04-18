<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location'];

    public function guards()
    {
        return $this->belongsToMany(Guard::class)
            ->withPivot('schedule')
            ->withTimestamps();
    }

    public function reports(): HasMany
    {
        return $this->hasMany(GuardReport::class);
    }
}
