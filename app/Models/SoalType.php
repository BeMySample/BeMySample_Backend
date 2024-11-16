<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalType extends Model
{
    use HasFactory;
    
    protected $table = 'soal_type';

    protected $fillable = [
        'icon',
        'type',
    ];
}
