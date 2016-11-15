<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Problem;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CourseController extends Controller
{
    const TEACHER_SEMESTER = "Teacher Semester";

    public function showCourses()
    {
        $courses = Auth::user()->courses->sortBy('courseName')->values();
        return view('course.showCourses', compact('courses'));
    }

    public function showAllCourses()
    {
        $courses = Course::orderBy('courseName')->where('isActive',1)->get();

        if(Auth::user()->role()->first()->roleName === 'Student'){
            foreach ($courses as $key => $c){
                if($c->semester()->first()->semesterName === 'Teacher Semester'){
                    unset($courses[$key]);
                }
            }
        }

        $joined_courses = Auth::user()->courses;
        foreach ($courses as $c) {
            $c->joined = false;
            foreach ($joined_courses as $jc) {
                if ($jc->courseId == $c->courseId) {
                    $c->joined = true;
                    break;
                }
            }
        }
        return view('course.showAllCourses', compact('courses'));
    }

    public function joinCourse($courseId)
    {
        $user = Auth::user();
        $user->courses()->attach($courseId);
        return redirect("/my-courses/$courseId/problems");
        //return $this->showProblems($courseId);
//        return Redirect::back();
    }

    public function leaveCourse($courseId)
    {
        $user = Auth::user();
        $user->courses()->detach($courseId);
        return Redirect::back();
    }

    public function showProblems($courseId)
    {
        $course = Auth::user()->courses->find($courseId);
        $problems = $course->problems()->paginate(10);
//        $problems = Problem::paginate(10);
        return view('course.showProblems', compact('problems','course'));
    }

    public function showProblemDetail($courseId, $problemId)
    {
        $courses = Auth::user()->courses->find($courseId);
        $problems = $courses->problems;
        $problem = $problems->find($problemId);
        //$submissions = Auth::user()->submissions($courseId, $problemId);
        return view('course.showProblemDetail', compact('courseId', 'problem'));
    }
}
