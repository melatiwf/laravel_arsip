<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengunjung extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'asal_instansi',
        'jumlah',
        'email',
    ];
    
    public function Kunjungans(): HasMany
    {
        return $this->hasMany(Kunjungan::class);
    }
}