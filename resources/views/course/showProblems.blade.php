@extends('layouts.app')

{{--@section('pageTitle')--}}
	{{--Môn học ...--}}
{{--@endsection--}}

@section('extendedHead')
@endsection

@section('script')
	<script src="{{URL::asset('js/codehub/animate.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $('#btn').click(function () {
            toastr.success("Gnome & Growl type non-blocking notifications", "Toastr Notifications");
        });

		showAnimation(".animate", "fadeInUp");
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