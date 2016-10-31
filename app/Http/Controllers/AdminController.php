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
		if (Auth::user()->roleId == 1) {
			$allcourses = Course::orderBy('courseName')->where('isActive',1)->get();
			$courses = Auth::user()->courses->find($courseId);
			$problems = $courses->problems;
			$problem = $problems->find($problemId);
			$row = DB::select(
					DB::raw(
						'SELECT courseName, courseId FROM courses WHERE courseId='.$courseId
					)
				);
			$courseName = $row[0]->courseName;
			$row = DB::select(
					DB::raw(
						'SELECT courseProblemId, hardLevel, scoreInCourse FROM courseproblems 
						WHERE courseId='.$courseId.' AND problemId='.$problemId
					)
				);
			$courseProblemInfo = $row[0];
        return view('admin.editProblem', compact('courseId', 'problem', 'courseName', 'allcourses', 'courseProblemInfo'));
		} else {
			return 'Access denied';
		}
    }
	
	public function addProblem(){
		if (Auth::user()->roleId == 1) {
			$courseId = 0;
			$problem = null;
			$allcourses = Course::orderBy('courseName')->where('isActive',1)->get();
			return view('admin.editProblem', compact('courseId', 'problem', 'allcourses'));
		} else {
			return 'Access denied';
		}
    }
	
	public function submitProblem(Request $request) {
		if (Auth::user()->roleId == 1) {
			try {
				if ($request->action == 'add') {
					
					////////////////////////ADD NEW PROBLEM
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
					
					//$addResult = $this->addProblemToCourse($this->problem->problemId, $courseId, $hardLevel, $scoreInCourse);
					DB::insert(
						'INSERT INTO courseproblems (courseId, problemId, hardLevel, scoreInCourse, isActive)
						VALUE (?, ?, ?, ?, ?)',
						[$courseId, $this->problem->problemId, $hardLevel, $scoreInCourse, 1]
					);
					return 'OK'.$this->problem->problemId;
				} else if ($request->action == 'edit') {
					
					////////////////////////EDIT PROBLEM
					$problemId = $request->input('problemId');
					$problemCode = $request->input('problemCode');
					$content = $request->input('problemContent');
					$inputDescription = $request->input('problemInput');
					$outputDescription = $request->input('problemOutput');
					$templateCode = '';
					$timelimit = $request->input('timeLimit');
					$defaultScore = $request->input('defaultScore');
					DB::table('problems')
						->where('problemId', $problemId)
						->update(['problemCode' => $problemCode, 
								'content' => $content,
								'inputDescription' => $inputDescription,
								'outputDescription' => $outputDescription,
								'templateCode' => $templateCode,
								'timelimit' => $timelimit,
								'defaultScore' => $defaultScore]);
					
					$courseProblemId = $request->input('courseProblemId');
					$scoreInCourse = $request->input('scoreInCourse');
					$hardLevel = $request->input('hardLevel');
					$courseId = $request->input('courseId');
					
					DB::table('courseproblems')
						->where('courseProblemId', $courseProblemId)
						->update(['scoreInCourse' => $scoreInCourse, 
								'hardLevel' => $hardLevel,
								'courseId' => $courseId]);
					return 'OK'.$problemId;
					
				} else if ($request->action == 'copy') {
					$courseId = $request->input('courseId');
					$problemId = $request->input('problemId');
					$hardLevel = $request->input('hardLevel');
					$scoreInCourse = $request->input('scoreInCourse');
					DB::insert(
						'INSERT INTO courseproblems (courseId, problemId, hardLevel, scoreInCourse, isActive)
						VALUE (?, ?, ?, ?, ?)',
						[$courseId, $problemId, $hardLevel, $scoreInCourse, 1]
					);
					return 'OK'.$problemId;
				} else {
					return 'Error';
				}
			} catch (\Exception $ex) {
				echo $ex->getMessage();
				return 'Error';
			}
		} else {
			return 'Access denied';
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
