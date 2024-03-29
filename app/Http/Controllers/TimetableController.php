<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\TimetableService;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    private $timetableService;

    public function __construct(TimetableService $timetableService)
    {
        $this->timetableService = $timetableService;
    }

    public function getTimetable(Course $course)
    {
        $courseWithSubjectsAndDayTimes = $course->load('subjects.subjectDayTimes');
        $timetable = $this->timetableService->generateTimetable($courseWithSubjectsAndDayTimes);
        return response()->json($timetable, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
