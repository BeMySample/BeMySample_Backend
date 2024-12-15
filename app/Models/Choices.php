<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choices extends Model
{
    protected $fillable = ['section_id', 'label', 'value'];

    public function section()
    {
        return $this->belongsTo(Sections::class);
    }
}
