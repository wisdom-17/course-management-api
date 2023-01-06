<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Resources\SubjectCollection;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use App\Models\SubjectDayTime;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new SubjectCollection(Subject::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubjectRequest $request)
    {
        $subject = new Subject();
        $subject->name = $request->name;
        $subject->course_calendar_id = $request->courseCalendarId;
        $subject->save();

        $subject->teachers()->attach($request->teacherIds);

        foreach ($request->daysTimes as $dayAndTime) {
            $subjectDayTime = new SubjectDayTime();
            $subjectDayTime->subject_id = $subject->id;
            $subjectDayTime->day = $dayAndTime['day'];
            $subjectDayTime->start_time = $dayAndTime['startTime'];
            $subjectDayTime->end_time = $dayAndTime['endTime'];
            $subjectDayTime->save();
        }

        return response()->json([
            'message' => 'Saved new Subject successfully',
            'id' => $subject->id
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
