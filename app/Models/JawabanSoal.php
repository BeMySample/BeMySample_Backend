<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSoal extends Model
{
    use HasFactory;
    
    protected $table = 'jawaban_soal';

    protected $fillable = [
        'responden_id',
        'jawaban',
    ];
}
