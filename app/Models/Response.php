<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = [
        'survey_id',
        'respondent_id',
        'section_id',
        'answer', 
    ];

    public function survey()
    {
        return $this->belongsTo(Surveys::class);
    }

    public function section()
    {
        return $this->belongsTo(Sections::class);
    }
}
