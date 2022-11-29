<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyCourseRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Carbon\Carbon;

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
            CourseResource::collection(Course::all())
        , 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseRequest $request)
    {
        $course = new Course();
        $course->name = $request->name;
        $course->start_date = Carbon::parse($request->startDate)->format('Y-m-d');
        $course->end_date = Carbon::parse($request->endDate)->format('Y-m-d');
        $course->teaching_days = $request->teachingDays;

        $course->save();

        return response()->json([
            'message' => 'Saved new Course successfully',
            'id' => $course->id
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
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->name = $request->name;
        $course->start_date = Carbon::parse($request->startDate)->format('Y-m-d');
        $course->end_date = Carbon::parse($request->endDate)->format('Y-m-d');
        $course->teaching_days = $request->teachingDays;
        $course->save();

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
        foreach ($request->courseIds as $id) {
            $course = Course::find($id);
            $course->delete(); // soft delete
        }

        return response()->json([
            'Deleted Course successfully'
        ], 200);
    }
}
