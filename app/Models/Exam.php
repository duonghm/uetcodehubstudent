<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Exam extends Model
{
    protected $primaryKey = 'examId';

    public function course()
    {
        return $this->belongsTo('App\Models\Course','courseId');
    }

    public function problems()
    {
        return $this->belongsToMany('App\Models\Problem', 'examproblems', 'examId', 'problemId')->withPivot('scoreInExam', 'isActive');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User', 'examusers', 'examId', 'userId');
    }

    public function isAvailable()
    {
        /*$now = new \DateTime();
        if($now >= $this->availableFrom && $now <= $this->availableTo){
            return true;
        }else{
            return false;
        }*/
//        $now = new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
        //$now = new \DateTime('now');
        //$now = date("Y-m-d H:i:s", strtotime('+7 hours'));
        $now = date("Y-m-d H:i:s");
        $now = strtotime ($now);
        $from = strtotime($this->availableFrom);
        $to = strtotime($this->availableTo);
        //$from = new \DateTime($this->availableFrom);
        //$to = new \DateTime($this->availableTo);

        return ($now >= $from) && ($now <= $to);
        //return $from >= $to;
    }

    public function hasJoin($userId)
    {
        $q = ExamUser::where('userId', $userId)->where('examId', $this->examId)->get();
        return sizeof($q) > 0;
    }

    public function joinTime($userId)
    {
        $q = ExamUser::where('userId', $userId)->where('examId', $this->examId)->get();
        if (sizeof($q) > 0) {
            return $q[0]->startTime;
        } else {
            return null;
        }

    }

    public function score($userId)
    {
        $scores = Submission::where('examId', $this->examId)->where('userId', $userId)->groupBy('problemId')
            ->get(['submitId', DB::raw('MAX(resultScore) as score')]);
        $total = 0;
        foreach ($scores as $score) {
            $total += $score['score'];
        }
        return $total;
    }

    public function maxScore(){
        $problems = $this->problems()->get();
        $total = 0;
        foreach ($problems as $p){
            $total += $p->defaultScore;
        }
        return $total;
    }
}
