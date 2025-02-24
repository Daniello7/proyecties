<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeyControl extends Model
{
    /** @use HasFactory<\Database\Factories\KeyControlFactory> */
    use HasFactory;

    protected array $dates = ['entry_time'];
    protected $fillable = [
        'type',
        'key_id',
        'person_id',
        'deliver_user_id',
        'receiver_user_id',
        'comment',
        'exit_time',
        'entry_time',
    ];

    public function key(): BelongsTo
    {
        return $this->belongsTo(Key::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function deliver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deliver_user_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }
}
