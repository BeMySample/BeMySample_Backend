<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    protected $table = 'data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'survey_id',
        'sectionName',
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

    public function section()
    {
        return $this->belongsTo(Surveys::class, 'survey_id');
    }
}
