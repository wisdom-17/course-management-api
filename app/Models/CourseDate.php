<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDate extends Model
{
    use HasFactory;

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
}
