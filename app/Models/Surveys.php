<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surveys extends Model
{
    use HasFactory;
    protected $table = 'surveys';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'backgroundImage',
        'bgColor',
        'createdByAI',
        'respondents',
        'maxRespondents',
        'coinAllocated',
        'coinUsed',
        'status',
        'surveyTitle',
        'surveyDescription',
        'thumbnail',
    ];

    public function sections()
    {
        return $this->hasMany(Sections::class, 'survey_id');
    }

    public function kriteria()
    {
        return $this->hasMany(SurveyKriteria::class, 'survey_id');
    }
    
}
