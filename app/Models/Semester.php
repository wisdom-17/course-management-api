<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get the Course Calendar
     */
    public function courseCalendar()
    {
        return $this->belongsTo(CourseCalendar::class);
    }

    /**
     * Get the course dates for the Semester
     */
    public function courseDates()
    {
        return $this->hasMany(CourseDate::class);
    }

}
