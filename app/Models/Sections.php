<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    protected $fillable = [
        'survey_id',
        'icon',
        'label',
        'bgColor',
        'bgOpacity',
        'buttonColor',
        'buttonText',
        'buttonTextColor',
        'contentText',
        'dateFormat',
        'description',
        'largeLabel',
        'listChoices',
        'maxChoices',
        'midLabel',
        'minChoices',
        'mustBeFilled',
        'optionsCount',
        'otherOption',
        'smallLabel',
        'textColor',
        'timeFormat',
        'title',
        'toggleResponseCopy',
    ];

    public function survey()
    {
        return $this->belongsTo(Surveys::class, 'survey_id');
    }

    // public function content()
    // {
    //     return $this->hasMany(Content::class, 'section_id');
    // }
}
