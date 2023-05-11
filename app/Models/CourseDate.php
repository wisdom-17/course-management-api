<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'semester_id', 'date_type_id', 'course_calendar_id', 'start_date', 'end_date'];

    /**
     * Get the date type
     */
    public function dateType()
    {
        return $this->belongsTo(DateType::class, 'type_id');
    }

    /**
     * Get the semester for the course date
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the Course Calendar for the course date
     */
    public function courseCalendar()
    {
        return $this->belongsTo(CourseCalendar::class);
    }
}
