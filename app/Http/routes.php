<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::auth();

Route::get('/', 'HomeController@index');
Route::get('/user','HomeController@user')->middleware('auth');
Route::get('/my-courses', 'CourseController@showCourses')->middleware('auth');
Route::get('/all-courses', 'CourseController@showAllCourses')->middleware('auth');
Route::get('/my-courses/{course_id}/problems', 'CourseController@showProblems')->middleware('auth');
Route::get('/my-courses/{course_id}/problems/{problem_id}', 'CourseController@showProblemDetail')->middleware('auth');
Route::get('/my-courses/{course_id}/problems/{problem_id}/submissionTable', 'SubmissionController@submissionDetail')->middleware('auth');
Route::get('/exams', 'ExamController@showExamCourses')->middleware('auth');
Route::get('/exams/{exam_id}', 'ExamController@startExam')->middleware('auth');
Route::get('/exams/{exam_id}/countDown','ExamController@countDown')->middleware('auth');
//Route::get('/exams/{exam_id}', 'ExamController@showExamDetail')->middleware('auth');
Route::get('/exams/{exam_id}/problems/{problem_id}', 'ExamController@showProblemDetail')->middleware('auth');
Route::get('/exams/{exam_id}/problems/{problem_id}/submissionTable', 'SubmissionController@examSubmissionDetail')->middleware('auth');
Route::get('/exams/{exam_id}/problems/{problem_id}/countDown','ExamController@countDown')->middleware('auth');



Route::get('/submitAjax', function(){
    if(Request::ajax()){
        return 'ajax data';
    }
});

Route::post('/join/{course_id}', 'CourseController@joinCourse')->middleware('auth');
Route::post('/leave/{course_id}', 'CourseController@leaveCourse')->middleware('auth');
Route::post('/submit/{course_id}/{problem_id}', 'JudgeController@submit')->middleware('auth');
//Route::post('/submit/{course_id}/{problem_id}', 'JudgeController@submitAjax')->middleware('auth');
Route::post('/submit-exam/{exam_id}/{problem_id}', 'JudgeController@submitExam')->middleware('auth');

//Route::post('/submitPostAjax', function(){
//    if(Request::ajax()){
//        //return var_dump(Request::all());
//        return $_POST['language'] . ' ' . $_POST['sourceCode'];
//    }
//});

Route::post('submitPostAjax', ['uses' => 'JudgeController@submitAjax']);
Route::post('/submitExam', ['uses' => 'JudgeController@submitExam']);

Route::post('/changePassword', ['uses' => 'HomeController@changePassword']);

\Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
    //var_dump($query->sql);
    //var_dump($query->bindings);
    //var_dump($query->time);
});