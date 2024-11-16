<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Survey extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'survey';

    protected $fillable = [
        'user_id',
        'judul_survey',
        'deskripsi_survey',
        'thumbnail',
        'status',
        'responden_now',
        'coin_allocated',
        'coin_used',
        'jumlah_soal',
        'desainAttr',
        'kriteria',
    ];
}
