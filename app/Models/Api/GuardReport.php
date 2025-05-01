<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardReport extends Model
{
    use HasFactory;

    protected $fillable = ['guard_id', 'zone_id', 'entry_time', 'exit_time', 'incident'];

    public function assignedGuard(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
}
