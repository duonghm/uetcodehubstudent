@extends('layouts.app')

@section('extendedHead')
    <link href="{{URL::asset('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet"
          type="text/css">
@endsection

@section('pageScript')
	<script src="{{URL::asset('assets/global/plugins/bootstrap-toastr/toastr.min.js')}}"
            type="text/javascript"></script>
@endsection

@section('script')
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$("#submit-button").click(function(){
	var _problemCode = $('#problemCode').val().toUpperCase();
	var _problemContent = $('#problemContent').val().replace(/\n/g, "<br/>\n");
	var _problemInput = $('#problemInput').val().replace(/\n/g, "<br/>\n");
	var _problemOutput = $('#problemOutput').val().replace(/\n/g, "<br/>\n");
	var _timeLimit = $('#timelimit').val();
	var _defaultScore = $('#score').val();
	var _isActive = $('#isactive').val();
	var _scoreInCourse = $('#scoreinc').val();
	var _hardLevel = $('#hardlevel').val();
	var _courseId = $('#selectCourse').val();
	$.ajax({
		type: "POST",
		url: "{{url('/admin/submitNewProblem')}}",
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
			courseId: _courseId
		},
		success: function (data) {
			console.log(data);//
			if (data == 'OK') {
				//alert('submit OK');
				toastr.success("Submission notifications", "Your submission is sent successfully");
			} else {
				//alert('something wrong');
				toastr.error("Submission notifications", "Error to submit submission");
			}
	
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(ajaxOptions);
			alert(thrownError);
		}
	});
});
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
                        NEW PROBLEM:<br/>
						<div style="text-align: justify; font-family: monospace; text-transform: uppercase">
							<textarea id="problemCode" style="height:32px; width:100%; text-transform: uppercase"></textarea>
						</div>
                    </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="box" id="problem-content" style="min-height: 420px;">
                    <div style="background: #E0E0E0; margin-top: 10px; font-weight: bold">Problem statement (auto add &lt;br/&gt;)</div>
                    <div class="box-content" style="text-align: justify; font-family: monospace;">
						<textarea id="problemContent" style="height:350px; width:100%;"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="col-md-6">
        <div id="pl_pr" class="portlet light portlet-fit full-height-content full-height-content-scrollable">
            <div class="portlet-body">
                <div class="box" id="problem-content" style="min-height: 600px;">
                    <div style="text-align: justify; font-family: monospace;">
                        <div style="background: #E0E0E0; margin-top: 10px; font-weight: bold;">Input Description (auto add &lt;br/&gt;)
                        </div>
                        <textarea id="problemInput" style="height:120px; width:100%;">&lt;code&gt;&lt;/code&gt;</textarea>
                    </div>
                    <div style="text-align: justify; font-family: monospace;">
                        <div style="background: #E0E0E0; margin-top: 10px; font-weight: bold;">Output Description (auto add &lt;br/&gt;)
                        </div>
                        <textarea id="problemOutput" style="height:120px; width:100%;">&lt;code&gt;&lt;/code&gt;</textarea>
                    </div>
					<br/>
					Course:
					<select id="selectCourse" style="width:100%;">
					@foreach ($allcourses as $course)
						<option value="{{$course->courseId}}">{{$course->courseName}}</option>
					@endforeach
					</select><br/><br/>
					Time limit: <input type="text" id="timelimit" value="1"/><br/>
					Hard level: <input type="text" id="hardlevel" value="1"/><br/>
					Score in course: <input type="text" id="scoreinc" value="0"/><br/>
					Total score: <input type="text" id="score" value="100"/><br/>
					Is Active: <input type="text" id="isactive" value="1"/><br/>
					<br/>
					<br/>
					<button class="btn btn-primary pull-right" id="submit-button">
                        DONE
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
