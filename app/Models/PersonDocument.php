<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonDocument extends Model
{
    use HasFactory;

    protected $fillable = ['filename', 'original_name', 'type', 'size', 'path'];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

}
