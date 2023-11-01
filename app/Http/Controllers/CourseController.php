<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyCourseRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\DateType;
use Carbon\Carbon;
use Illuminate\Support\Str;

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
        $course->save();

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
                $course->courseDates()->create([
                    'name' => $date['name'],
                    'start_date' => Carbon::parse($date['startDate'])->format('Y-m-d'),
                    'end_date' => Carbon::parse($date['endDate'])->format('Y-m-d'),
                    'date_type_id' => $dateType->id,
                    'course_id' => $course->id
                ]);
            }
        }

        foreach ($semesterTermsAndHolidays as $semesterName => $semesterData) {
            $semester = $course->semesters()->create([
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
            'message' => 'Saved new Course Course successfully',
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
        $course->save();

        // TODO: handle semesters here?
        // Will this be edited on the same page/request as semesters? 
        // Validation rules? What if there a terms belonging to the semester?

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
        // TODO: handle related semesters, terms and subjects
        foreach ($request->courseIds as $id) {
            $course = Course::find($id);
            $course->delete(); // soft delete
        }

        return response()->json([
            'Deleted Course Course(s) successfully'
        ], 200);
    }
}
