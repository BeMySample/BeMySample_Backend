<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surveys extends Model
{
    protected $fillable = ['survey_title', 'active_section', 'background_image', 'bg_color', 'created_by_ai', 'respondents', 'status'];

    public function sections()
    {
        return $this->hasMany(Sections::class, 'survey_id');
    }
}
