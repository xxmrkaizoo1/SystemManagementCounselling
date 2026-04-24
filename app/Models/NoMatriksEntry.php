<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoMatriksEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_matriks',
        'created_by',
    ];
}
