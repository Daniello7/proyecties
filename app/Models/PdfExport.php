<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdfExport extends Model
{
    protected $fillable = ['user_id', 'file_path', 'type', 'viewed_at'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
