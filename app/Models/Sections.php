<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    protected $fillable = ['survey_id', 'section_id', 'icon', 'label'];

    public function survey()
    {
        return $this->belongsTo(Surveys::class, 'survey_id');
    }

    public function content()
    {
        return $this->hasMany(Content::class, 'section_id');
    }
}
