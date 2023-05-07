<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyCourseRequest;
use App\Http\Requests\StoreCourseCalendarRequest;
use App\Http\Requests\UpdateCourseCalendarRequest;
use App\Http\Resources\CourseCalendarResource;
use App\Models\CourseCalendar;
use App\Models\DateType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

        // Structure terms and holidays by semester
        $semesterTermsAndHolidays = [];
        foreach ($request->terms as $term) {
            if (array_key_exists('semester', $term)) {
                $semesterTermsAndHolidays[$term['semester']]['terms'][] = $term;
            }
        }

        foreach ($request->holidays as $holiday) {
            if (array_key_exists('semester', $holiday)) {
                $semesterTermsAndHolidays[$holiday['semester']]['holidays'][] = $holiday;
            }
        }

        // get terms and holidays not belonging to a semester
        $nonSemesterTerms = array_filter($request->terms, fn($term) => array_key_exists('semester', $term) === false);
        $nonSemesterHolidays = array_filter($request->holidays, fn($holiday) => array_key_exists('semester', $holiday) === false);

        // handle terms and holidays not belonging to a semester
        $nonSemesterDates = [
            'terms' => $nonSemesterTerms,
            'holidays' => $nonSemesterHolidays
        ];

        // save non-semester terms and holidays
        foreach($nonSemesterDates as $type => $dates) {
            $dateType = DateType::where('type', Str::singular($type))->first();
            foreach ($dates as $date) {
                $courseCalendar->courseDates()->create([
                    'name' => $date['name'],
                    'start_date' => Carbon::parse($date['startDate'])->format('Y-m-d'),
                    'end_date' => Carbon::parse($date['endDate'])->format('Y-m-d'),
                    'date_type_id' => $dateType->id,
                    'course_calendar_id' => $courseCalendar->id
                ]);
            }
        }

        foreach ($semesterTermsAndHolidays as $semesterName => $semesterData) {
            $semester = $courseCalendar->semesters()->create([
                'name' => $semesterName
            ]);
            foreach ($semesterData as $type => $dates) {
                $dateType = DateType::where('type', Str::singular($type))->first();
                foreach ($dates as $date) {
                    $semester->courseDates()->create([
                        'name' => $date['name'],
                        'start_date' => Carbon::parse($date['startDate'])->format('Y-m-d'),
                        'end_date' => Carbon::parse($date['endDate'])->format('Y-m-d'),
                        'date_type_id' => $dateType->id
                    ]);
                }
            }
        }

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
    public function update(UpdateCourseCalendarRequest $request, CourseCalendar $courseCalendar)
    {
        $courseCalendar->name = $request->name;
        $courseCalendar->start_date = Carbon::parse($request->startDate)->format('Y-m-d');
        $courseCalendar->end_date = Carbon::parse($request->endDate)->format('Y-m-d');
        $courseCalendar->save();

        // TODO: handle semesters here?
        // Will this be edited on the same page/request as semesters? 
        // Validation rules? What if there a terms belonging to the semester?

        return response()->json([
            'message' => 'Updated Course successfully',
            'id' => $courseCalendar->id
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
        // TODO: handle related semesters, terms and subjects
        foreach ($request->courseCalendarIds as $id) {
            $course = CourseCalendar::find($id);
            $course->delete(); // soft delete
        }

        return response()->json([
            'Deleted Course Calendar(s) successfully'
        ], 200);
    }
}
