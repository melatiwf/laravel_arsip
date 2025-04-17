<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kunjungan extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'nama_instansi',
        'tanggal',
        'tujuan_kunjungan',
        'pengunjungs_id',

    ];
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/kunjungans/' . $image),
        );
    }
    
    public function pengunjungs()
    {
        return $this->belongsTo(Pengunjung::class, 'pengunjungs_id');
    }

}