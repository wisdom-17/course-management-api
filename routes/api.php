<?php

use App\Http\Controllers\TokenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseCalendarController;
use App\Http\Controllers\CourseDateController;
use App\Http\Controllers\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/sanctum/token', TokenController::class);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/users/auth', AuthController::class);

    // - defined outside of apiResources because we want to
    // delete multiple courses using this endpoint
    // 
    // - defined as a PATCH request because we are soft deleting (according to REST this is not DELETE )
    // soft deleting just changes the value of the deleted_at column
    Route::patch('/course-calendars', [CourseCalendarController::class, 'destroy']);

    // we don't want the default destroy route, as it supports deleting one record 
    // we want to delete multiple in one request hence the above
    Route::apiResource('course-calendars', CourseCalendarController::class)->except('destroy');

    Route::apiResources([
        'course-calendars.dates' => CourseDateController::class,
        'teachers' => TeacherController::class
    ]);
    
});
