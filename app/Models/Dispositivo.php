<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dispositivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'marca_id',
        'modelo',
        'precio',
        'stock',
        'imagen',
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}