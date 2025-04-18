<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
