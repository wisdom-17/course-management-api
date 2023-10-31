<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function subjectDayTimes()
    {
        return $this->hasMany(SubjectDayTime::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function courseCalendar()
    {
        return $this->belongsTo(CourseCalendar::class);
    }
}
