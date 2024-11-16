<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontribusiExplore extends Model
{
    use HasFactory;
    
    protected $table = 'kontribusi_explore';

    protected $fillable = [
        'id_author',
        'thumbnail',
        'judul',
        'coin',
        'kriteria',
    ];
}
