<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    protected $table = 'content';
    protected $fillable = [
        'section_id',
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

    // public function section()
    // {
    //     return $this->belongsTo(Sections::class,'section_id');
    // }
}
