<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function bisnis()
    {
        return $this->belongsTo(Bisnis::class, 'nama_bisnis');
    }
}
