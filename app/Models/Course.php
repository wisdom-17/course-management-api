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
     * Get dates for the course
     */
    public function courseDates()
    {
        return $this->hasMany(CourseDate::class);
    }
}
