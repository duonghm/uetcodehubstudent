<?php
/**
 * Created by PhpStorm.
 * User: hmduong
 * Date: 6/12/2016
 * Time: 4:08 PM
 */

namespace App\CustomClass{

    use Illuminate\Support\Facades\DB;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\Auth;

    class Statistic{

        public function getNumberOfCourse(){
            return \App\Models\Course::count();
        }

        public function getNumberOfMember(){
            return \App\Models\User::all()->where('roleId',4)->count();

        }

        public function getNumberOfExercise(){
            return \App\Models\Problem::count();
        }

        public function getTopCourses(){
            return \App\Models\Course::all()->where("isActive", 1);

        }

        public function getSampleExercise(){
            return \App\Models\Problem::orderBy(\DB::raw('RAND()'))->take(10)->get();

        }

        public function getRankingTable(){
            return DB::select(
                DB::raw(
                    'select rankingTable.userId, rankingTable.username, rankingTable.totalScore, rankingTable.rank from
                  (select userId, username, totalScore,
                    @curRank := IF(@prevRank = totalScore, @curRank, @incRank) AS rank, 
                    @incRank := @incRank + 1, 
                    @prevRank := totalScore
                  from
                    (select b.userId as userId, b.username as username, sum(b.maxScore) as totalScore
                    from 
                      (select users.userId, users.username, submissions.problemId, submissions.courseId, COALESCE(max(resultScore),-1) as maxScore
                      from submissions right join users on submissions.userId = users.userId
                      group by users.userId, problemId, courseId) as b
                    group by b.userId order by totalScore desc) as c,
                    (SELECT @curRank :=0, @prevRank := NULL, @incRank := 1) as r 
                  ) as rankingTable'
                )
            );
        }

        /*public function getRankingTable(){
            return DB::select(
                DB::raw(
                    'select rankingTable.userId, rankingTable.username, rankingTable.totalScore, rankingTable.rank from
                  (select userId, username, totalScore,
                    @curRank := IF(@prevRank = totalScore, @curRank, @incRank) AS rank,
                    @incRank := @incRank + 1,
                    @prevRank := totalScore
                  from
                    (select b.userId as userId, b.username as username, sum(b.maxScore) as totalScore
                    from
                      (select users.userId, users.username, submissions.problemId, submissions.courseId, COALESCE(max(resultScore),-1) as maxScore
                      from submissions right join users on submissions.userId = users.userId
                      group by users.userId, problemId, courseId) as b
                    group by b.userId order by totalScore desc) as c,
                    (SELECT @curRank :=0, @prevRank := NULL, @incRank := 1) as r
                  ) as rankingTable'
                )
            );
        }*/
		
		public function getHardestTable(){
            return DB::select(
                DB::raw(
                    'SELECT problemId, numOfUser, numOfFinishedUser, ratio FROM
						(SELECT tab1.problemId, tab1.numOfUser, tab2.numOfFinishedUser, COALESCE((tab2.numOfFinishedUser/tab1.numOfUser),-1) as ratio
						FROM (select problemId, count(userId) as numOfUser from(
											select problems.problemId, submissions.userId, max(submissions.resultScore) as userScore, submissions.courseId, problems.defaultScore
											from problems left join submissions on problems.problemId = submissions.problemId
											group by problems.problemId, submissions.userId) as s
										group by problemId) as tab1
						LEFT OUTER JOIN (select problems.problemId as problemId, count(s.userId) as numOfFinishedUser from(
											select problems.problemId, submissions.userId, max(submissions.resultScore) as userScore, submissions.courseId, problems.defaultScore
											from problems left join submissions on problems.problemId = submissions.problemId
											group by problems.problemId, submissions.userId having userScore = defaultScore) as s
											right join problems on s.problemId = problems.problemId
											group by problems.problemId) as tab2
						ON tab1.problemId=tab2.problemId) as mytab
					WHERE mytab.ratio > 0
					ORDER BY ratio'
                )
            );
        }
		
		public function getCourseTable(){
            return DB::select(
                DB::raw(
                    'SELECT tab1.courseId, tab2.courseName, tab1.numOfProblems FROM
						(SELECT courseId, COUNT(courseId) AS numOfProblems
						FROM courseproblems
						GROUP BY courseId) AS tab1
					LEFT JOIN
						(SELECT courseId, courseName
						FROM courses) AS tab2
					ON tab1.courseId = tab2.courseId'
                )
            );
        }
		
		public function getNumOfUnsubmit(){
			$row = DB::select(
				DB::raw(
'SELECT COUNT(probId) AS result FROM
	(SELECT probId, tab1.courseId, isActive
	FROM (SELECT tab1.courseId, tab1.problemId AS probId, isAct  FROM
					(SELECT courseproblems.courseId, problemId, isAct
					FROM courseproblems
					LEFT JOIN (SELECT courseId, isActive as isAct
						FROM courses
						WHERE isActive = 1) AS coursesTab
					ON courseproblems.courseId = coursesTab.courseId
					WHERE courseproblems.isActive = 1 AND isAct IS NOT NULL
					) AS tab1
				LEFT JOIN
					(SELECT courseId, userId
					FROM courseusers
					WHERE userId = '.Auth::user()->userId.'
					) AS tab2
				ON tab1.courseId = tab2.courseId
				WHERE userId IS NOT NULL
		) AS tab1
	LEFT JOIN (SELECT problemId, userId, isActive FROM submissions
		WHERE userId = '.Auth::user()->userId.'
		GROUP BY problemId)
		AS tab2
	ON probId = tab2.problemId
	WHERE userId IS NULL
	GROUP BY tab2.problemId)
AS mytab'
				)
			);
			return ($row[0]->result);
		}

    }

}

