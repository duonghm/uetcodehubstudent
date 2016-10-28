<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    protected $primaryKey = 'userId';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'firstname', 'lastname', 'email', 'password', 'roleId'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFullname()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function role(){
        return $this->hasOne('App\Models\Userrole', 'roleId');
    }

    public function problems()
    {
        return $this->hasMany('App\Models\Problem');
    }

    public function courses()
    {
        return $this->belongsToMany('App\Models\Course', 'courseusers', 'userId', 'courseId')->where('isActive',1);
    }

    public function exams(){
        return $this->belongsToMany('App\Models\Exam', 'examusers', 'userId', 'examId');
    }

    public function submissions($courseId, $problemId)
    {
        $condition = ['courseId' => $courseId, 'problemId' => $problemId];
        return
            $this->hasMany('App\Models\Submission', 'userId')
                ->where($condition)->orderBy('submitId', 'desc')->get();
    }

    public function examSubmissions($examId, $problemId){
        $condition = ['examId' => $examId, 'problemId' => $problemId];
        return
            $this->hasMany('App\Models\Submission', 'userId')
                ->where($condition)->orderBy('submitId', 'desc')->get();
    }

    public function allSubmissions()
    {
        return $this->hasMany('App\Models\Submission', 'userId')->orderBy('submitId', 'desc');
    }

    public function totalScore()
    {
        $tbl = $this
            ->hasMany('App\Models\Submission', 'userId')
            ->groupBy('courseId', 'problemId')
            ->get(['submissions.submitId', \DB::raw('max(resultScore) as maxScore')])
            ->sum('maxScore');
        return $tbl;
    }

    protected $rankingTable;
    public function calculateRanking(){
        $this->rankingTable = DB::select(
            DB::raw(
                'SELECT tab2.userId, tab1.username, tab1.firstname, tab1.lastname, tab2.score AS totalScore, tab2.rank
				FROM users AS tab1
				RIGHT JOIN rankingtable AS tab2
				ON tab1.userId = tab2.userId
				ORDER BY tab2.rank'
            )
        );
    }

    public function currentRanking()
    {
        $currentRank = DB::select(
            DB::raw(
                'SELECT tab2.userId, tab1.username, tab1.firstname, tab1.lastname, tab2.score AS totalScore, tab2.rank
				FROM users AS tab1
				RIGHT JOIN rankingtable AS tab2
				ON tab1.userId = tab2.userId
				WHERE tab2.userId='.$this->userId
            )
        );
		if (sizeof($currentRank)<1) {
			return 'Updating';
		} else {
			return $currentRank[0]->rank;
		}

    }

    public function totalUserNumber(){
        return $this->get()->count();
    }

    /* Remove remember token */
    public function getRememberToken()
    {
        return null; // not supported
    }

    public function setRememberToken($value)
    {
        // not supported
    }

    public function getRememberTokenName()
    {
        return null; // not supported
    }

    /**
     * Overrides the method to ignore the remember token.
     */
    public function setAttribute($key, $value)
    {
        $isRememberTokenAttribute = $key == $this->getRememberTokenName();
        if (!$isRememberTokenAttribute) {
            parent::setAttribute($key, $value);
        }
    }

}
