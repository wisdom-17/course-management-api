<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SubjectCollection;
use App\Models\Subject;
use App\Models\SubjectDayTime;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () use ($request, $subject) {
            $subject->name = $request->name;
            $subject->course_calendar_id = $request->courseCalendarId;
            $subject->save();

            $subject->teachers()->attach($request->teacherIds);

            foreach ($request->daysTimes as $dayAndTime) {
                $subjectDayTime = new SubjectDayTime();
                $subjectDayTime->subject()->associate($subject);
                $subjectDayTime->day = $dayAndTime['day'];
                $subjectDayTime->start_time = $dayAndTime['startTime'];
                $subjectDayTime->end_time = $dayAndTime['endTime'];
                $subjectDayTime->save();
            }
        });

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
     * @param  Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        DB::transaction(function () use ($subject, $request) {
            $subject->name = $request->name;
            $subject->course_calendar_id = $request->courseCalendarId;
            $subject->save();
    
            $subject->teachers()->sync($request->teacherIds);
    
            $subject->subjectDayTimes()->delete();
            foreach ($request->daysTimes as $dayAndTime) {
                $subjectDayTime = new SubjectDayTime();
                $subjectDayTime->subject()->associate($subject);
                $subjectDayTime->day = $dayAndTime['day'];
                $subjectDayTime->start_time = $dayAndTime['startTime'];
                $subjectDayTime->end_time = $dayAndTime['endTime'];
                $subjectDayTime->save();
            }
        });

        return response()->json([
            'message' => 'Updated subject successfully',
            'id' => $subject->id
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->subjectDayTimes()->delete();
        $subject->teachers()->sync([]);
        $subject->delete(); // soft delete

        return response()->json([
            'Deleted subject successfully'
        ], 200);
    }
}
