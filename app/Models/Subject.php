<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public function subjectDayTimes()
    {
        return $this->hasMany(SubjectDayTime::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }
}
