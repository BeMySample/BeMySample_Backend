<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveySoal extends Model
{
    use HasFactory;
    
    protected $table = 'survey_soal';

    protected $fillable = [
        'survey_id',
        'heading',
        'desc',
        'tipe_soal',
        'jawaban',
    ];
}
