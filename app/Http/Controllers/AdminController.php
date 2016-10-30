<?php
/**
 * Created by PhpStorm.
 * User: ngxson
 * Date: 10/29/2016
 * Time: 4:31 PM
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Models\Course;
use App\Models\Problem;

use Mockery\CountValidator\Exception;

class AdminController extends Controller {
    
    public function editProblem($courseId, $problemId){
        return view('admin.editProblem', compact('courseId', 'problemId'));
    }
	
	public function addProblem(){
		$courseId = 0;
		$problemId = 0;
		$allcourses = Course::orderBy('courseName')->where('isActive',1)->get();
        return view('admin.editProblem', compact('courseId', 'problemId', 'allcourses'));
    }
	
	public function submitNewProblem(Request $request) {
        try {
            $this->problem = new Problem();
            $this->problem->problemCode = $request->input('problemCode');
            $this->problem->userId = 1;
            $this->problem->content = $request->input('problemContent');
            $this->problem->inputDescription = $request->input('problemInput');
            $this->problem->outputDescription = $request->input('problemOutput');
            $this->problem->templateCode = '';
            $this->problem->timelimit = $request->input('timeLimit');
            $this->problem->defaultScore = $request->input('defaultScore');
            $this->problem->isActive = $request->input('isActive');
            $this->problem->save();
			
			$scoreInCourse = $request->input('scoreInCourse');
			$hardLevel = $request->input('hardLevel');
			$courseId = $request->input('courseId');
			
			//$addResult = addProblemToCourse($this->problem->problemId, $courseId, 
			//$addResult = addProblemToCourse(103, $courseId, $hardLevel, $scoreInCourse);
			DB::insert(
                'INSERT INTO courseproblems (courseId, problemId, hardLevel, scoreInCourse, isActive)
				VALUE (?, ?, ?, ?, ?)',
				[$courseId, $this->problem->problemId, $hardLevel, $scoreInCourse, 1]
            );
			return 'OK'.$this->problem->problemId;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            return 'Error';
        }
    }
	
	public function addProblemToCourse($problemId, $courseId, $hardLevel, $scoreInCourse) {
		try {
            DB::insert(
                'INSERT INTO courseproblems (courseId, problemId, hardLevel, scoreInCourse, isActive)
				VALUE (?, ?, ?, ?, ?)',
				[$courseId, $problemId, $hardLevel, $scoreInCourse, 1]
            );
			return 0;
		} catch (\Exception $ex) {
            return $ex->getMessage();
        }
	}
    
}
