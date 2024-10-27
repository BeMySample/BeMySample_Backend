<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'deskripsi', 'kategori'
    ];

    public function peneliti() {
        return $this->belongsTo(User::class, 'id_peneliti');
    }

    // public function surveyRespondens() {
    //     return $this->hasMany(SurveyResponden::class, 'id_survey');
    // }
}
