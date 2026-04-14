<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'id_periksa',
        'id_pasien',
        'bukti_pembayaran',
        'status',
    ];

    public function periksa()
    {
        return $this->belongsTo(Periksa::class, 'id_periksa');
    }

    public function pasien()
    {
        return $this->belongsTo(User::class, 'id_pasien');
    }
}
