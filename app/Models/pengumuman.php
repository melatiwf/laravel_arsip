<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Pengumuman extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'judul',
        'tanggal_dibuat',
        'tampil_hingga',

    ];
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/pengumumans/' . $image),
        );
    }

}