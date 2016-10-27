<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Problem extends Model
{
    protected $primaryKey = 'problemId';
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function courses()
    {
        return $this->belongsToMany('App\Models\Course', 'courseProblems', 'courseId', 'problemId');
    }

    public function submissions()
    {
        return $this->hasMany('App\Models\Submission', 'submitId');
    }

    public function exams()
    {
        return $this->belongsToMany('App\Models\Exam', 'examProblem')->withPivot('scoreInExam', 'isActive');
    }

    public function numberOfSubmitedUser2(){
        $row = DB::select(
            DB::raw(
                'SELECT problemId, submittedUser AS numOfUser 
				FROM problemsolvingresult
				WHERE problemId = '.$this->problemId)
        );
        return $row[0]->numOfUser;
    }

    /*public function numberOfSubmitedUser2(){
        $p = Submission::where('problemId',$this->problemId)->groupBy('userId')->get([DB::raw('userId')]);
        return sizeof($p);
    }*/

    public function numberOfFinishedUser2(){
        $row = DB::select(
            DB::raw(
                'SELECT problemId, finishedUser AS numOfUser 
				FROM problemsolvingresult
				WHERE problemId = '.$this->problemId)
        );
        return $row[0]->numOfUser;
    }
	
	public function getProblemSolvingResult() {
		$row = DB::select(
            DB::raw(
                'SELECT problemId, submittedUser, finishedUser 
				FROM problemsolvingresult
				WHERE problemId = '.$this->problemId)
        );
		return $row;
	}

    /*public function numberOfFinishedUser2(){
        $submissions = Submission::where('problemId',$this->problemId)->groupBy('userId')->get(['userId', DB::raw('max(resultScore) as score')]);
        $count = 0;
        foreach ($submissions as $s){
            if($s->score == $this->defaultScore){
                $count++;
            }
        }
        return $count;
    }*/

    public function getScoreOfUser($courseId){
        $row = DB::select(
            DB::raw(
                'select problemId, courseId, userId, max(resultScore) as score
                 from submissions where userid = '.Auth::user()->userId.' and courseId = '.$courseId. ' and problemId = '.$this->problemId
            )
        );
        return $row[0]->score;
    }

    public function getScoreOfUserInExam($examId){
        $row = DB::select(
            DB::raw(
                'select problemId, examId, userId, max(resultScore) as score
                 from submissions where userid = '.Auth::user()->userId.' and examId = '.$examId. ' and problemId = '.$this->problemId
            )
        );
        return $row[0]->score;
    }
}
