<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseDateRequest;
use App\Models\Course;
use App\Models\CourseDate;
use App\Models\DateType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseDateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseDateRequest $request, Course $course)
    {
        $dateType = DateType::where('type', $request->dateType)->first();

        foreach ($request->dates as $dateRange) {
            $courseDate = new CourseDate();
            $courseDate->course_id = $course->id;
            $courseDate->date_type_id = $dateType->id;
            $courseDate->start_date = Carbon::parse($dateRange[0])->format('Y-m-d');
            $courseDate->end_date = Carbon::parse($dateRange[1])->format('Y-m-d');

            $courseDate->save();
        }

        return response()->json([
            'Saved Course dates successfully'
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
