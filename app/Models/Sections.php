<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    protected $fillable = ['survey_id', 'section_id', 'title', 'description', 'content_text', 'bg_color', 'bg_opacity', 'button_text', 'button_color', 'button_text_color', 'text_color', 'must_be_filled', 'max_choices', 'min_choices', 'options_count', 'other_option', 'large_label', 'mid_label', 'small_label'];

    public function survey()
    {
        return $this->belongsTo(Surveys::class, 'survey_id');
    }

    public function choices()
    {
        return $this->hasMany(Choices::class, 'section_id');
    }
}
