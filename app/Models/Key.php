<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Key extends Model
{
    /** @use HasFactory<\Database\Factories\KeyFactory> */
    use HasFactory;

    const ZONES = [
        'Office',
        'Entrance',
        'Factory',
        'Parking',
        'Control Access'
    ];

    protected $fillable = ['name', 'zone'];

    public function keyControls(): HasMany
    {
        return $this->hasMany(KeyControl::class);
    }
}
