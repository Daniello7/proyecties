<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    /** @use HasFactory<\Database\Factories\PackageFactory> */
    use HasFactory;

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


}
