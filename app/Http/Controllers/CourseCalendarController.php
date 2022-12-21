<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyCourseRequest;
use App\Http\Requests\StoreCourseCalendarRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseCalendarResource;
use App\Models\CourseCalendar;
use Carbon\Carbon;

class CourseCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            CourseCalendarResource::collection(CourseCalendar::all())
        , 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseCalendarRequest $request)
    {
        $courseCalendar = new CourseCalendar();
        $courseCalendar->name = $request->name;
        $courseCalendar->start_date = Carbon::parse($request->startDate)->format('Y-m-d');
        $courseCalendar->end_date = Carbon::parse($request->endDate)->format('Y-m-d');

        $courseCalendar->save();

        return response()->json([
            'message' => 'Saved new Course Calendar successfully',
            'id' => $courseCalendar->id
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseRequest $request, CourseCalendar $courseCalendar)
    {
        $courseCalendar->name = $request->name;
        $courseCalendar->start_date = Carbon::parse($request->startDate)->format('Y-m-d');
        $courseCalendar->end_date = Carbon::parse($request->endDate)->format('Y-m-d');
        $courseCalendar->save();

        return response()->json([
            'message' => 'Updated Course successfully',
            'id' => $course->id
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Array  $courseIds
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyCourseRequest $request)
    {
        foreach ($request->courseCalendarIds as $id) {
            $course = CourseCalendar::find($id);
            $course->delete(); // soft delete
        }

        return response()->json([
            'Deleted Course Calendar(s) successfully'
        ], 200);
    }
}
