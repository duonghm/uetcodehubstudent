@extends('layouts.app')

@section('extendedHead')
	<style>
		.animate {
			opacity:0;
		}
	</style>
@endsection

@section('pageScript')


    <script src="{{URL::asset('assets/global/plugins/counterup/jquery.waypoints.min.js')}}"
            type="text/javascript"></script>
    <script src="{{URL::asset('assets/global/plugins/counterup/jquery.counterup.min.js')}}"
            type="text/javascript"></script>


@endsection

@section('script')
    <script type="text/javascript">
        $('#btn').click(function () {
            toastr.success("Gnome & Growl type non-blocking notifications", "Toastr Notifications");
        });

		showAnimation(".animate", "fadeInUp");
		
		function animateView(element, delay, animClass) {
			setTimeout(function() {
				element.addClass(animClass+' animated');
				element.css({"opacity": "1"});
			}, delay);
		}
		
		function showAnimation(elemClass, animClass) {
			var del = 0;
			$(elemClass).each( function() {
				del += 100;
				$(this).css({"opacity": "0"});
				animateView($(this), del, animClass);
			});
		}
    </script>
@endsection

@section('pageTitle')
    <?php 
	date_default_timezone_set("Asia/Bangkok");
	$hour = intval(date('H'));
		if ($hour > 22) {
			$greating = "Good night";
		} else if ($hour > 12) {
			$greating = "Good afternoon";
		} else if ($hour > 4) {
			$greating = "Good morning";
		} else {
			$greating = "Good night";
		} 
		
	if(!Auth::guest()) {
		//$unsubmitted = $statistic->getNumOfUnsubmit();
		$unsubmittedProblems = $statistic->getUnsubmittedProblems(); $countProbleme = 1;
		$unsubmitted = sizeof($unsubmittedProblems);
		$coursesummary = $statistic->getCourseSummary();
	}
	
	$coursetable = $statistic->getCourseTable();
	$totalExercises = 0;
	$totalCourses = 0;
	foreach($coursetable as $course) {
		$totalCourses++;
        $totalExercises += $course->numOfProblems;
	}
	?>
	{{$greating}}@if(!Auth::guest()), {{Auth::user()->getFullname()}}@endif!
@endsection

@section('content')
    <div class="row">
    @if(!Auth::guest())
		<a href="{{url('/my-courses')}}">
            <div class="animate col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat blue" id="anim1">
                    <div class="visual">
                        <i class="fa fa-comments"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="" data-value="">{{Auth::user()->courses()->count()}}</span>
                        </div>
                        <div class="desc"> Your courses</div>
                    </div>
                    <a class="more" href="{{url('/my-courses')}}"> View more
                        <i class="m-icon-swapright m-icon-white"></i>
                </div>
            </div>
		</a>
		<a href="{{url('/user')}}">
            <div class="animate col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat red" id="anim2">
                    <div class="visual">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup"
                                  data-value="{{Auth::user()->allSubmissions()->count()}}">0</span>
                        </div>
                        <div class="desc"> Total Submissions</div>
                    </div>
                    <a class="more" href="{{url('/user')}}"> View more
                        <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
		</a>
		<a href="{{url('/user')}}">
            <div class="animate col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat green" id="anim3">
                    <div class="visual">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="{{Auth::user()->totalScore()}}">0</span>
                        </div>
                        <div class="desc"> Total Score</div>
                    </div>
                    <a class="more" href=""> View more
                        <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
		</a>
		<a href="{{url('/user')}}">
            <div class="animate col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat purple" id="anim4">
                    <div class="visual">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="details">
                        <div class="number">

                            <span data-counter="counterup" 
								data-value="{{Auth::user()->currentRanking()}}">0</span>
                        </div>
                        <div class="desc"> Ranking</div>
                    </div>
                    <a class="more" href="{{url('/user')}}"> View more
                        <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
		</a>
@else
            <div class="animate col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-10">
                <div class="dashboard-stat blue" id="anim1">
                    <div class="visual">
                        <i class="fa fa-briefcase fa-icon-medium"></i>
                    </div>
                    <div class="details">
                        <div class="number"> {{$totalExercises}}</div>
                        <div class="desc"> Total Exercises</div>
                    </div>
                    <a class="more" href="javascript:;">
                        <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
            <div class="animate col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="dashboard-stat red" id="anim2">
                    <div class="visual">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="details">
                        <div class="number"> {{$statistic->getNumberOfMember()}}</div>
                        <div class="desc"> Total Members</div>
                    </div>
                    <a class="more" href="javascript:;">
                        <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
            <div class="animate col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="dashboard-stat green" id="anim3">
                    <div class="visual">
                        <i class="fa fa-group fa-icon-medium"></i>
                    </div>
                    <div class="details">
                        <div class="number"> {{$totalCourses}}</div>
                        <div class="desc"> Total Courses</div>
                    </div>
                    <a class="more" href="javascript:;">
                        <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
        </div>
@endif
@if(Auth::guest())
        <div class="row">
            <div class="animate col-lg-4 col-md-4 col-sm-6 col-xs-12" id="anim4">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-user font-blue"></i>
                            <span class="caption-subject font-blue bold uppercase">LOGIN NOW</span>
                        </div>
                    </div>
                    <div class="portlet-body">
						<form class="login-form" role="form" method="POST" action="{{ url('/login') }}">
							{!! csrf_field() !!}
							<div class="alert alert-danger display-hide">
								<button class="close" data-close="alert"></button>
								<span> Enter any username and password. </span>
							</div>
						
							@if ($errors->has('username'))
							<div class="alert alert-danger">
								<span><strong>{{ $errors->first('username') }}</strong></span>
							</div>
							@endif
							@if ($errors->has('password'))
							<div class="alert alert-danger">
								<span class="help-block">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
							</div>
							@endif
						
							<div class="form-group">
								<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
								<label class="control-label visible-ie8 visible-ie9">Username</label>
								<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off"
									placeholder="Username" name="username"/></div>
							<div class="form-group">
								<label class="control-label visible-ie8 visible-ie9">Password</label>
								<input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"
									placeholder="Password" name="password"/>
							</div>
							<input type="checkbox" name="remember" value="1"/>Remember </label><br/><br/>
							<div class="form-actions">
								<center><button type="submit" class="btn green uppercase" style="width:100%"
									data-toggle="modal" data-target="#enroll-modal">Login</button></center>
								<label class="rememberme check">
							</div>
						</form>

                    </div>
                </div>
            </div>
			<div class="animate col-lg-8 col-md-8 col-sm-6 col-xs-12" id="anim5">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa-area-chart font-blue"></i>
                            <span class="caption-subject font-blue bold uppercase">Course Info.</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="scroller" style="height: 210px;" data-always-visible="1"
                             data-rail-visible1="0" data-handle-color="#D7DCE2">
                            <table class="table table-hover table-light">
                                <thead>
                                <tr class="uppercase">
                                    <th width="80%"> Course name<br/><br/></th>
                                    <th width="20%"> Num. Of Problems</th>
                                </tr>
                                </thead>
                                @foreach($coursetable as $course)
                                    <tr>
                                        <td> {{$course->courseName}}</td>
                                        <td> {{$course->numOfProblems}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
@endif

@if(!Auth::guest())
<div class="animate col-lg-6 col-md-6 col-sm-12 col-xs-12" id="anim5">
	   <div class="portlet light ">
		   <div class="portlet-title">
			   <div class="caption">
				   <i class="icon-share font-blue"></i>
				   <span class="caption-subject font-blue bold uppercase">NOT SUBMITTED PROBLEMS</span>
			   </div>
		   </div>
		   <div class="portlet-body">
			   <div class="scroller" style="height: 210px;" data-always-visible="1"
					data-rail-visible1="0" data-handle-color="#D7DCE2">
				   <table class="table table-hover table-light">
					   <thead>
					   <tr class="uppercase">
						   <th width="10%"></th>
						   <th> Name</th>
						   <th> Course</th>
					   </tr>
					   </thead>
					   @foreach($unsubmittedProblems as $course)
						   <tr>
							   <td>
								   <a href="javascript:;"
									  class="primary-link">{{$countProbleme}}</a>
							   </td>
							   <td> 
								<a href="{{url('/my-courses/'.$course->courseId.'/problems/'.$course->problemId)}}">
									{{$course->problemCode}}
								</a>
							</td>
							   <td>
								   {{$course->courseName}}
							   </td>
						   </tr>
					<?php $countProbleme = $countProbleme + 1; ?>
					   @endforeach
				   </table>
			   </div>
		   </div>
	   </div>
   </div>
<div class="animate col-lg-6 col-md-6 col-sm-12 col-xs-12" id="anim5">
	   <div class="portlet light ">
		   <div class="portlet-title">
			   <div class="caption">
				   <i class="icon-share font-blue"></i>
				   <span class="caption-subject font-blue bold uppercase">SUMMARY</span>
			   </div>
		   </div>
		   <div class="portlet-body">
			   <div class="scroller" style="height: 210px;" data-always-visible="1"
					data-rail-visible1="0" data-handle-color="#D7DCE2">
				   <table class="table table-hover table-light">
					   <thead>
					   <tr class="uppercase">
						   <th> Course</th>
						   <th> Remain</th>
						   <th> Rate</th>
					   </tr>
					   </thead>
					   @foreach($coursesummary as $course)
						   <tr>
							   <td>
								   <a href="{{url('/my-courses/'.$course->courseId)}}">
									{{$course->courseName}}
								</a>
							   </td>
							<td> 
								{{$course->numOfProblem - $course->numOfDoneProblem}} problems
							</td>
							   <td>
								   {{round(($course->numOfDoneProblem*100/$course->numOfProblem), 2)}}%
							   </td>
						   </tr>
					   @endforeach
				   </table>
			   </div>
		   </div>
	   </div>
</div>
@endif

        </div>
		<script src="{{URL::asset('assets/pages/scripts/login.min.js')}}" type="text/javascript"></script>

@endsection
