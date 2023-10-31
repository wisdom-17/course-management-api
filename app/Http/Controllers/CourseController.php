<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\CourseCalendar;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            CourseResource::collection(CourseCalendar::all())
        , 200);
    }
}
