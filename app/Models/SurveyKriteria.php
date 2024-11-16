<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyKriteria extends Model
{
    use HasFactory;
    
    protected $table = 'survey_kriteria';

    protected $fillable = [
        'gender_target',
        'age_target',
        'lokasi',
        'hobi',
        'pekerjaan',
        'tempat_bekerja',
        'responden_target',
        'poin_foreach',
    ];
}
