@extends('layouts.app')

@section('extendedHead')
@endsection

@section('pageScript')


    <script src="{{URL::asset('assets/global/plugins/counterup/jquery.waypoints.min.js')}}"
            type="text/javascript"></script>
    <script src="{{URL::asset('assets/global/plugins/counterup/jquery.counterup.min.js')}}"
            type="text/javascript"></script>
	<script src="{{URL::asset('js/codehub/animate.js')}}"
            type="text/javascript"></script>


@endsection

@section('script')
    <script type="text/javascript">
        $('#btn').click(function () {
            toastr.success("Gnome & Growl type non-blocking notifications", "Toastr Notifications");
        });

		showAnimation(".animate", "fadeInUp");
    </script>
@endsection

@section('pageTitle')
    <?php 
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
	Xin chào @if(!Auth::guest()){{Auth::user()->getFullname()}}@endif!
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
                        <div class="desc">Khóa học của bạn</div>
                    </div>
                    <a class="more" href="{{url('/my-courses')}}"> Xem thêm
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
                        <div class="desc"> Số lần đã nộp</div>
                    </div>
                    <a class="more" href="{{url('/user')}}"> Xem thêm
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
                        <div class="desc"> Tổng điểm</div>
                    </div>
                    <a class="more" href="{{url('/user')}}"> Xem thêm
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
                        <div class="desc"> Xếp hạng</div>
                    </div>
                    <a class="more" href="{{url('/user')}}"> Xem thêm
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
                        <div class="desc"> Tổng số bài</div>
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
                        <div class="desc"> Số thành viên</div>
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
                        <div class="desc"> Số khóa học</div>
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
                            <span class="caption-subject font-blue bold uppercase">ĐĂNG NHẬP</span>
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
							<input type="checkbox" name="remember" value="1"/>Nhớ đăng nhập </label><br/><br/>
							<div class="form-actions">
								<center><button type="submit" class="btn green uppercase" style="width:100%"
									data-toggle="modal" data-target="#enroll-modal">ĐĂNG NHẬP</button></center>
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
                            <span class="caption-subject font-blue bold uppercase">CÁC KHÓA HỌC</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="scroller" style="height: 210px;" data-always-visible="1"
                             data-rail-visible1="0" data-handle-color="#D7DCE2">
                            <table class="table table-hover table-light">
                                <thead>
                                <tr class="uppercase">
                                    <th width="80%"> Tên khóa học<br/><br/></th>
                                    <th width="20%"> Số bài tập</th>
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
				   <span class="caption-subject font-blue bold uppercase">CÁC BÀI CHƯA THỬ LÀM</span>
			   </div>
		   </div>
		   <div class="portlet-body">
			   <div class="scroller" style="height: 210px;" data-always-visible="1"
					data-rail-visible1="0" data-handle-color="#D7DCE2">
				   <table class="table table-hover table-light">
					   <thead>
					   <tr class="uppercase">
						   <th width="10%"></th>
						   <th> Mã bài</th>
						   <th> Khóa học</th>
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
				   <span class="caption-subject font-blue bold uppercase">TÌNH TRẠNG HOÀN THÀNH</span>
			   </div>
		   </div>
		   <div class="portlet-body">
			   <div class="scroller" style="height: 210px;" data-always-visible="1"
					data-rail-visible1="0" data-handle-color="#D7DCE2">
				   <table class="table table-hover table-light">
					   <thead>
					   <tr class="uppercase">
						   <th> Khóa học</th>
						   <th> Chưa làm</th>
						   <th> Tỷ lệ</th>
					   </tr>
					   </thead>
					   @foreach($coursesummary as $course)
						   <tr>
							   <td>
								   <a href="{{url('/my-courses/'.$course->courseId.'/problems/')}}">
									{{$course->courseName}}
								</a>
							   </td>
							<td> 
								{{$course->numOfProblem - $course->numOfDoneProblem}} bài
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
