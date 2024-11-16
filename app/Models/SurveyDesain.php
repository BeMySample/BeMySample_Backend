<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyDesain extends Model
{
    use HasFactory;
    
    protected $table = 'survey_desain';

    protected $fillable = [
        'background_img',
        'opacity',
        'warna_latar',
        'warna_tombol',
        'warna_tombol_text',
        'warna_text',
    ];
}
