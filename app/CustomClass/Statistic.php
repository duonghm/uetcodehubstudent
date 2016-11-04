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
                    'SELECT tab2.userId, tab1.username, tab1.firstname, tab1.lastname, tab2.score AS totalScore, tab2.rank
					FROM users AS tab1
					RIGHT JOIN rankingtable AS tab2
					ON tab1.userId = tab2.userId
					ORDER BY tab2.rank'
                )
            );
        }
		
		public function getHardestTable(){
            return DB::select(
                DB::raw(
                    'SELECT problemId, problemCode, tab1.courseId, ratio,
					submittedUser AS numOfUser, finishedUser AS numOfFinishedUser FROM
						courses AS tab1
					RIGHT JOIN 
						(SELECT problems.problemId, tab1.courseId, finishedUser, submittedUser,
							COALESCE(finishedUser/submittedUser, -1) AS ratio, problemCode
						FROM 
							(SELECT psr.problemId, psr.courseId, finishedUser, submittedUser
							FROM problemsolvingresult AS psr
							LEFT JOIN examproblems
							ON psr.problemId=examproblems.problemId
							WHERE isActive IS NULL) AS tab1
						LEFT JOIN problems
						ON tab1.problemId = problems.problemId
						WHERE submittedUser > 40) AS tab2
					ON tab2.courseId=tab1.courseId
					WHERE isActive=1
					ORDER BY ratio
					LIMIT 0,10'
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
						FROM courses
						WHERE isActive=1) AS tab2
					ON tab1.courseId = tab2.courseId
					WHERE tab2.courseName IS NOT NULL'
                )
            );
        }
		
		/*
			Explain:
			- Get all courses with isActive = 1
			- Then get all problems with isActive = 1 in those courses:
				courseproblems LEFT JOIN above = tab1
			- Get your courses => tab2
				tab1 LEFT JOIN tab2 = all problems you're having (tab11)
			- Get list of my submitted problems (tab12)
				tab11 LEFT JOIN tab12
				count NULL rows => unsubmitted problems
		*/
		public function getNumOfUnsubmit(){
			$row = DB::select(
				DB::raw(
					'SELECT COUNT(problemId) AS result FROM
						(SELECT tab11.problemId, tab11.courseId
						FROM (SELECT tab1.problemId, tab1.courseId, isAct  FROM
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
							) AS tab11
						LEFT JOIN (SELECT problemId, userId, isActive FROM submissions
							WHERE userId = '.Auth::user()->userId.'
							GROUP BY problemId)
							AS tab12
						ON tab11.problemId = tab12.problemId
						WHERE userId IS NULL)
					AS mytab'
				)
			);
			return ($row[0]->result);
		}
		
		public function getUnsubmittedProblems() {
			return DB::select(
				DB::raw(
					'SELECT a.problemId, a.courseId, problemCode FROM (
						SELECT tab11.problemId, tab11.courseId
						FROM (SELECT tab1.problemId, tab1.courseId, isAct  FROM
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
							) AS tab11
						LEFT JOIN (SELECT * FROM userproblemscore
							WHERE userId = '.Auth::user()->userId.'
							) AS tab12
						ON tab11.problemId = tab12.problemId AND tab11.courseId = tab12.courseId
						WHERE userId IS NULL
					) AS a
					LEFT JOIN problems
					ON a.problemId = problems.problemId'
				)
			);
		}

    }

}

