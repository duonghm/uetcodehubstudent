@extends('layouts.app')

@section('script')
	<script>
		var remainingTime = {{$remainTime}};
		var countDownElem = document.getElementById('countDownTimer');
		var minutes, seconds;
		
		function timeTick() {
			minutes = Math.floor(remainingTime/60);
			seconds = remainingTime%60;
			if (remainingTime > 300) {
				countDownElem.style.color = 'cornflowerblue';
			} else if (remainingTime > 0) {
				countDownElem.style.color = '#df8505';
			} else {
				countDownElem.style.color = '#e7505a';
			}
			
			if (minutes > 0)
				countDownElem.innerHTML = "Remaining time: " + minutes + "m " + seconds + "s";
			else if (minutes == 0)
				countDownElem.innerHTML = "Remaining time: " + seconds + "s";
			else {
				countDownElem.innerHTML = "TIME IS UP";
				if (remainingTime > -2)
					toastr.success("Congratulation!", "You've finished your test.");
				clearInterval(mTimer);
			}
			remainingTime--;
		}
		
		var mTimer = setInterval(timeTick, 1000);
	</script>
@endsection

@section('content')
    <div class="portlet light portlet-fit full-height-content full-height-content-scrollable ">
        <div class="portlet-title">
            <div class="caption" style="width: 100%">
                <i class=" icon-layers font-green"></i>
                <span class="caption-subject font-green bold uppercase">{{$exam->examName}}: </span>
                <span class="caption-subject bold" style="color:#00232c;"> {{$exam->score(Auth::user()->userId). '/' . $exam->maxScore()}}</span>
                <div class="caption-subject" style="float: right; width: 60%; font-family: inherit;font-weight: bold; color: cornflowerblue;">
                    <span id="countDownTimer">Loading timer...</span>
                </div>
            </div>
        </div>
        <div class="portlet-body">
            @if(sizeof($problems) > 0)
                <div class="table-scrollable table-scrollable-borderless">
                    <table class="table table-hover table-light">
                        <thead>
                        <tr>
                            <th>Tên bài</th>
                            <th>Đề bài</th>
                            <th>Điểm tối đa</th>
                            <th>Điểm hiện tại</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($problems as $index=>$problem)
                            <tr>
                                <td>Bài {{$index + 1}}</td>

                                <td>{!! $problem->content !!} </td>
{{--                                <td>{{$problem->pivot->scoreInExam}} </td>--}}
                                <td>{{$problem->defaultScore}}</td>
                                <td>
                                    @php
                                        $problemScore = $problem->getScoreOfUserInExam($exam->examId);
                                    @endphp
                                    @if($problemScore == null)
                                        <span style="color:#bf3030; font-weight: bold">Chưa nộp bài</span>
                                    @elseif($problemScore == $problem->defaultScore )
                                        <span style="color: #1b8b5c; font-weight: bold">Hoàn thành ({{$problemScore}})</span>
                                    @else
                                        <span style="color: gray; font-weight: bold">Còn sai sót ({{$problemScore}})</span>
                                    @endif
                                </td>
                                <td><a href="{{url('/exams/'.$exam->examId.'/problems/'.$problem->problemId)}}">Làm
                                        bài</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                Chưa có bài tập nào!
            @endif

        </div>
    </div>

@endsection