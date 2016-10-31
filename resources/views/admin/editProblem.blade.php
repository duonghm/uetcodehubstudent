@extends('layouts.app')

@section('extendedHead')
    <link href="{{URL::asset('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet"
          type="text/css">
@endsection

@section('pageScript')
	<script src="{{URL::asset('assets/global/plugins/bootstrap-toastr/toastr.min.js')}}"
            type="text/javascript"></script>
<?php
	if($problem != null) {
		$_problemCode = $problem->problemCode;
		$_problemId = $problem->problemId;
		$_timeLimit = $problem->timelimit;
		$_hardLevel = $courseProblemInfo->hardLevel;
		$_defaultScore = $problem->defaultScore;
		$_scoreInCourse = $courseProblemInfo->scoreInCourse;
		$_isActive = $problem->isActive;
		$_courseProblemId = $courseProblemInfo->courseProblemId;
		$_action = 'edit';
	} else {
		$_problemId = 0;
		$_problemCode = '';
		$_timeLimit = 1;
		$_hardLevel = 1;
		$_defaultScore = 100;
		$_scoreInCourse = 0;
		$_isActive = 1;
		$_courseProblemId = 0;
		$_action = 'add';
	}

?>
@endsection

@section('script')
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$("#submit-button").click(function(){
	var r = confirm("Confirm ?");
	if (r == true) {
		sendSubmit("{{ $_action }}");
	}
});

$("#copy-button").click(function(){
	var r = confirm("Confirm ?");
	if (r == true) {
		sendSubmit("copy");
	}
});

function sendSubmit(_action) {
	var _problemId = {{ $_problemId }};
	var _problemCode = $('#problemCode').val().toUpperCase();
	var _problemContent = $('#problemContent').val().replace(/\n/g, "<br/>\n");
	var _problemInput = $('#problemInput').val().replace(/\n/g, "<br/>\n");
	var _problemOutput = $('#problemOutput').val().replace(/\n/g, "<br/>\n");
	var _timeLimit = $('#timelimit').val();
	var _defaultScore = $('#score').val();
	//var _isActive = $('#isactive').val();
	var _isActive = 1;
	//var _scoreInCourse = $('#scoreinc').val();
	var _scoreInCourse = 0;
	var _hardLevel = $('#hardlevel').val();
	var _courseId = $('#selectCourse').val();
	$.ajax({
		type: "POST",
		url: "{{url('/admin/submitProblem')}}",
		timeout: 5000,
		data: {
			problemCode: _problemCode,
			problemContent: _problemContent,
			problemInput: _problemInput,
			problemOutput: _problemOutput,
			timeLimit: _timeLimit,
			defaultScore: _defaultScore,
			isActive: _isActive,
			//courseproblem
			scoreInCourse: _scoreInCourse,
			hardLevel: _hardLevel,
			courseId: _courseId, 
			courseProblemId: {{ $_courseProblemId }},
			problemId: {{ $_problemId }},
			action: _action
		},
		success: function (data) {
			console.log(data);//
			if (data.substring(0, 2) == 'OK') {
				//alert('submit OK');
				window.location = "{{url('/my-courses')}}/"+_courseId+"/problems/"+data.match(/\d+/)[0];
				toastr.success("Success!", "New problem has been added");
			} else {
				//alert('something wrong');
				alert(data);
				toastr.error("Submission notifications", "Error to submit submission");
			}
	
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(ajaxOptions);
			alert(thrownError);
		}
	});
}
</script>
@endsection

@section('pageTitle')
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div id="pl_pr" class="portlet light portlet-fit full-height-content full-height-content-scrollable">
            <div class="portlet-title">
                <div class="caption" style="width:100%;">
                    <span class="caption-subject font-blue bold uppercase">
						@if($problem == null)
							NEW PROBLEM:<br/>
						@else
							EDIT PROBLEM:<br/>
						@endif
						<div style="text-align: justify; font-family: monospace; text-transform: uppercase">
							<textarea id="problemCode" style="height:32px; width:100%; text-transform: uppercase">{{ $_problemCode }}</textarea>
						</div>
                    </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="box" id="problem-content" style="min-height: 420px;">
                    <div style="background: #E0E0E0; margin-top: 10px; font-weight: bold">Problem statement (auto add &lt;br/&gt; on submit)</div>
                    <div class="box-content" style="text-align: justify; font-family: monospace;">
						@if($problem != null)
							<textarea id="problemContent" style="height:350px; width:100%;">{{ str_replace('<br/>','',$problem->content) }}</textarea>
						@else
							<textarea id="problemContent" style="height:350px; width:100%;"></textarea>
						@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="col-md-6">
        <div id="pl_pr" class="portlet light portlet-fit full-height-content full-height-content-scrollable">
            <div class="portlet-body">
                <div class="box" id="problem-content" style="min-height: 550px;">
                    <div style="text-align: justify; font-family: monospace;">
                        <div style="background: #E0E0E0; margin-top: 10px; font-weight: bold;">Input Description (auto add &lt;br/&gt; on submit)
                        </div>
						@if($problem != null)
							<textarea id="problemInput" style="height:120px; width:100%;">{{ str_replace('<br/>','',$problem->inputDescription) }}</textarea>
						@else
							<textarea id="problemInput" style="height:120px; width:100%;">&lt;code&gt;&lt;/code&gt;</textarea>
						@endif
                    </div>
                    <div style="text-align: justify; font-family: monospace;">
                        <div style="background: #E0E0E0; margin-top: 10px; font-weight: bold;">Output Description (auto add &lt;br/&gt; on submit)
                        </div>
                        @if($problem != null)
							<textarea id="problemOutput" style="height:120px; width:100%;">{{ str_replace('<br/>','',$problem->outputDescription) }}</textarea>
						@else
							<textarea id="problemOutput" style="height:120px; width:100%;">&lt;code&gt;&lt;/code&gt;</textarea>
						@endif
                    </div>
					<br/>
					Course:
					<select id="selectCourse" style="width:100%;">
					@foreach ($allcourses as $course)
						@if($course->courseId == $courseId)
							<option selected="selected" value="{{$course->courseId}}">{{$course->courseName}}</option>
						@else
							<option value="{{$course->courseId}}">{{$course->courseName}}</option>
						@endif
					@endforeach
					</select><br/><br/>
					Time limit: <input type="text" id="timelimit" value="{{ $_timeLimit }}"/><br/>
					Hard level: <input type="text" id="hardlevel" value="{{ $_hardLevel }}"/><br/>
					<!--Score in course: <input type="text" id="scoreinc" value="{{ $_scoreInCourse }}"/><br/-->
					Total score: <input type="text" id="score" value="{{ $_defaultScore }}"/><br/>
					<!--Is Active: <input type="text" id="isactive" value="1"/><br/-->
					<br/>
					<br/>
                    @if($problem == null)
						<button class="btn btn-primary pull-right" id="submit-button">ADD PROBLEM</button>
					@else
						<button class="btn btn-primary" id="copy-button">Copy to course</button>
						<button class="btn btn-primary pull-right" id="submit-button">EDIT PROBLEM</button>
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
