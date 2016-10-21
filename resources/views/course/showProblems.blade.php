@extends('layouts.app')

{{--@section('pageTitle')--}}
	{{--Môn học ...--}}
{{--@endsection--}}

@section('content')

	<div class="portlet light portlet-fit full-height-content full-height-content-scrollable ">
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
		$problemScore = $p->getScoreOfUser($course->courseId);
	@endphp
	<div class="portlet light">
		<div class="portlet-body">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-xs-12">
					<b><a href="{{url(Request::path().'/'.$p->problemId)}}">Exer. {{$startIndex + $index + 1}} | {{$p->problemCode}}</a></b><br/>
					{{--<td width="300px">{{$p->tagValues}}</td>--}}
					{{--<td>{{$p->pivot->hardLevel}}</td>--}}
					Submitted: {{$p->numberOfSubmitedUser2()}} users<br/> 
					Finished: {{$p->numberOfFinishedUser2()}} users<br/>
					<button type="button"
						onclick="document.location = '{{url(Request::path().'/'.$p->problemId)}}';"
					@if($problemScore == null)
						class="btn btn-primary" > <b>SOLVE THIS PROBLEM</b> </button>
					@elseif($problemScore == $p->defaultScore)
						class="btn btn-success" > <i class="fa fa-check"></i> <b>REVIEW</b> ({{$problemScore}})</button>
					@else
						class="btn btn-warning" style="color: #000;"> <b>TRY AGAIN</b> ({{$problemScore}}) </button>
					@endif
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8 hidden-xs hidden-xxs">
						@php
							$summary = strip_tags($p->content);
						@endphp
						{!! substr($summary, 0, 400) !!}
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