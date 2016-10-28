@extends('layouts.app')

{{--@section('pageTitle')--}}
	{{--Môn học ...--}}
{{--@endsection--}}

@section('script')
    <script type="text/javascript">
        $('#btn').click(function () {
            toastr.success("Gnome & Growl type non-blocking notifications", "Toastr Notifications");
        });

		showAnimation(".animate", "fadeInRight");
		
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

@section('content')

	<div class="animate portlet light portlet-fit full-height-content full-height-content-scrollable ">
		<div class="portlet-title">
			<div class="caption">
				<i class=" icon-layers font-green"></i>
				<span class="caption-subject font-green bold uppercase">{{$course->courseName}}</span>
			</div>
		</div>
		<div style="text-align: center">{!! $problems->render() !!}</div>
	</div>
	<br/>
@if(sizeof($problems) > 0)
@foreach($problems as $index=>$p)
	@php
		$startIndex = ($problems->currentPage()-1) * $problems->perPage();
		$problemscore = $p->getScoreOfUser($course->courseId);
		$problemsolveresult = $p->getProblemSolvingResult($p->pivot->courseProblemId);
		if (sizeof($problemsolveresult) == 0) {
			$submitted = 'Updating';
			$finished = 'Updating';
		} else {
			$submitted = $problemsolveresult[0]->submittedUser.' users';
			$finished = $problemsolveresult[0]->finishedUser.' users';
		}
	@endphp
	<div class="animate portlet light">
		<div class="portlet-body">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-xs-12">
					<b><a href="{{url(Request::path().'/'.$p->problemId)}}?page={{$problems->currentPage()}}">Exer. {{$problems->total() - $startIndex - $index}} | {{$p->problemCode}}</a></b><br/>
					{{--<td width="300px">{{$p->tagValues}}</td>--}}
					{{--<td>{{$p->pivot->hardLevel}}</td>--}}
					Submitted: {{$submitted}}<br/> 
					Finished: {{$finished}}<br/>
					<button type="button"
						onclick="document.location = '{{url(Request::path().'/'.$p->problemId)}}?page={{$problems->currentPage()}}';"
					@if($problemscore == null)
						class="btn btn-primary" > <b>SOLVE THIS PROBLEM</b> </button>
					@elseif($problemscore == $p->defaultScore)
						class="btn btn-success" > <i class="fa fa-check"></i> <b>REVIEW</b> ({{$problemscore}})</button>
					@else
						class="btn btn-warning" style="color: #000;"> <b>TRY AGAIN</b> ({{$problemscore}}) </button>
					@endif
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8 hidden-xs hidden-xxs">
						@php
							$summary = strip_tags($p->content);
							$summary = str_replace(array("\r\n", "\n", "\r"), ' ', $summary);
						@endphp
						<script>
							document.write(decodeURI("{{ $summary }}").substr(0,400));
						</script>
						@if(strlen($summary) >= 400)
							...
						@endif
				</div>
			</div>
		</div>
	</div>
	@endforeach
	<div style="text-align: center">{!! $problems->render() !!}</div>
@else
	Chưa có bài tập nào!
@endif


@endsection