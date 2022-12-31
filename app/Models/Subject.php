<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public function subjectDaysTimes()
    {
        return $this->hasMany(SubjectDayTime::class);
    }
}
