<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guard extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'dni'];

    public function zones()
    {
        return $this->belongsToMany(Zone::class)
            ->withPivot('schedule')
            ->withTimestamps();
    }
}
