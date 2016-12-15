@extends('layouts.app')

{{--@section('pageTitle')--}}
	{{--Môn học ...--}}
{{--@endsection--}}

@section('extendedHead')
<style>
@media only screen and (max-width: 800px) {
	
	/* Force table to not be like tables anymore */
	#no-more-tables table, 
	#no-more-tables thead, 
	#no-more-tables tbody, 
	#no-more-tables th, 
	#no-more-tables td, 
	#no-more-tables tr { 
		display: block; 
	}
 
	/* Hide table headers (but not display: none;, for accessibility) */
	#no-more-tables thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
 
	#no-more-tables tr { border: 1px solid #ccc; }
 
	#no-more-tables td { 
		/* Behave  like a "row" */
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
		white-space: normal;
		text-align:left;
	}
 
	#no-more-tables td:before { 
		/* Now like a table header */
		position: absolute;
		/* Top/left values mimic padding */
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
		text-align:left;
		font-weight: bold;
	}
 
	/*
	Label the data
	*/
	#no-more-tables td:before { content: attr(data-title); }
}
</style>
	<style>
		.animate {
			opacity:0;
		}
	</style>
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
        <div class="portlet-body">
            @if(sizeof($problems) > 0)
                <div class="table-scrollable table-scrollable-borderless">
                    <div style="text-align: center">{!! $problems->render() !!}</div>
					<section id="no-more-tables">
                    <table class="table-bordered table-striped table-condensed table table-hover table-light">
                        <thead>
							<tr>
								<th width="10%">STT</th>
								{{--<th>Tag value</th>--}}
								{{--<th>Độ khó</th>--}}
								<th width="30%">Mã bài</th>
								<th width="10%">Điểm tối đa</th>
								<th width="10%">Đã nộp</th>
								<th width="10%">Đã hoàn thành</th>
								<th width="20%">Điểm hiện tại</th>
							</tr>
						</thead>
                        <tbody>
                        @foreach($problems as $index=>$p)
							@php
								$startIndex = ($problems->currentPage()-1) * $problems->perPage();
								$problemscore = $p->getScoreOfUser($course->courseId);
								$problemsolveresult = $p->getProblemSolvingResult($p->pivot->courseProblemId);
								if (sizeof($problemsolveresult) == 0) {
									$submitted = 'Updating';
									$finished = 'Updating';
								} else {
									$submitted = $problemsolveresult[0]->submittedUser.' người';
									$finished = $problemsolveresult[0]->finishedUser.' người';
								}
							@endphp
                            <tr class="animate">
                                @php
                                    $startIndex = ($problems->currentPage()-1) * $problems->perPage();
                                @endphp
                                <td data-title="STT">
									<a href="{{url(Request::path().'/'.$p->problemId)}}">Bài {{$problems->total() - $startIndex - $index}}</a>
								</td>
									{{--<td width="300px">{{$p->tagValues}}</td>--}}
									{{--<td>{{$p->pivot->hardLevel}}</td>--}}
                                <td data-title="Mã bài">{{$p->problemCode}}</td>
                                <td data-title="Điểm tối đa">{{$p->defaultScore}}</td>
                                <td data-title="Đã nộp">{{$submitted}}</td>
                                @php
                                    $problemScore = $p->getScoreOfUser($course->courseId);
                                @endphp
                                <td data-title="Đã hoàn thành">
                                    {{$finished}}
                                </td>
                                <td data-title="Điểm hiện tại">
                                    <button type="button"
										onclick="document.location = '{{url(Request::path().'/'.$p->problemId)}}?page={{$problems->currentPage()}}';"
									@if($problemscore == null)
										class="btn btn-primary" > <b>LÀM BÀI NÀY</b> </button>
									@elseif($problemscore == $p->defaultScore)
										class="btn btn-success" > <i class="fa fa-check"></i> <b>HOÀN THÀNH</b></button>
									@else
										class="btn btn-warning" style="color: #000;"> <b>THỬ LẠI</b> ({{$problemscore}}) </button>
									@endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
					</section>
                    <div style="text-align: center">{!! $problems->render() !!}</div>
                </div>
            @else
                Chưa có bài tập nào!
            @endif

        </div>
    </div>



@endsection