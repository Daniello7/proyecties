<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentExport extends Model
{
    protected $fillable = ['user_id', 'filename','file_path', 'type', 'viewed_at'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
