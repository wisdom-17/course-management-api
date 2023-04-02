<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCalendar extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Get dates for the Course Calendar
     */
    public function courseDates()
    {
        return $this->hasMany(CourseDate::class);
    }

    /**
     * Get Semesters for the Course Calendar
     */
    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }
}
