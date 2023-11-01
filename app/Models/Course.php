<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Get dates for the Course Course
     */
    public function courseDates()
    {
        return $this->hasMany(CourseDate::class);
    }

    /**
     * Get Semesters for the Course Course
     */
    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    /**
     * Get Subjects for the Course Course
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

}
