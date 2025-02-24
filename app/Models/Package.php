<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Package extends Model
{
    /** @use HasFactory<\Database\Factories\PackageFactory> */
    use HasFactory;

    protected $fillable = ['type', 'agency', 'package_count', 'external_entity', 'receiver_user_id',
        'deliver_user_id', 'internal_person_id', 'retired_by', 'entry_time', 'exit_time', 'comment'];

    const AGENCIES = [
        'AZKAR',
        'CBL',
        'CHRONO EXPRESS',
        'CORREOS',
        'CORREOS EXPRESS',
        'DACHSER',
        'DHL',
        'DSV',
        'ENVIALIA',
        'FEDEX',
        'FUENSANTA EXPRESS',
        'GLS',
        'LODITRANS',
        'MRW',
        'NACEX',
        'NAVATRANS',
        'NCH',
        'ORDIMUR',
        'RDA',
        'REDUR',
        'REDYSER',
        'SENDING',
        'SEUR',
        'SIN AGENCIA',
        'SUS MEDIOS',
        'TDN',
        'TIPS@',
        'TNT',
        'TOURLINE EXPRESS',
        'TRANSAHER',
        'TSB',
        'TXT',
        'UPS',
        'VIAEXPRESS',
        'ZELERIS'
    ];

    public function internalPerson(): BelongsTo
    {
        return $this->belongsTo(InternalPerson::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    public function deliver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deliver_user_id');
    }
}
