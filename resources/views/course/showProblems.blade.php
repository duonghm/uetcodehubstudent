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
        <div class="portlet-body">
            @if(sizeof($problems) > 0)
                <div class="table-scrollable table-scrollable-borderless">
                    <div style="text-align: center">{!! $problems->render() !!}</div>
                    <table class="table table-hover table-light">
                        <thead>
                        <tr>
                            <th width="10%">Thứ tự</th>
                            {{--<th>Tag value</th>--}}
                            {{--<th>Độ khó</th>--}}
                            <th width="20%">Mã bài</th>
                            <th width="10%">Điểm tối đa</th>
                            <th width="10%">Đã nộp</th>
                            <th width="10%">Đã hoàn thành</th>
                            <th width="30%">Điểm hiện tại</th>
                        </tr>
                        <tbody>
                        @foreach($problems as $index=>$p)
                            <tr>
                                @php
                                    $startIndex = ($problems->currentPage()-1) * $problems->perPage();
                                @endphp
                                <td><a href="{{url(Request::path().'/'.$p->problemId)}}">Bài {{$startIndex + $index + 1}}</a></td>
                                {{--<td width="300px">{{$p->tagValues}}</td>--}}
                                {{--<td>{{$p->pivot->hardLevel}}</td>--}}
                                <td>{{$p->problemCode}}</td>
                                <td>{{$p->defaultScore}}</td>
                                <td>{{$p->numberOfSubmitedUser2()}} người</td>
                                @php
                                    $problemScore = $p->getScoreOfUser($course->courseId);
                                @endphp
                                <td>
                                    {{$p->numberOfFinishedUser2()}} người
                                </td>
                                <td>
                                    @if($problemScore == null)
                                        <span style="color:#bf3030; font-weight: bold">Chưa nộp bài</span>
                                    @elseif($problemScore == $p->defaultScore)
                                        <span style="color: #1b8b5c; font-weight: bold">Hoàn thành ({{$problemScore}})</span>
                                    @else
                                        <span style="color: gray; font-weight: bold">Còn sai sót ({{$problemScore}})</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        </thead>
                    </table>
                    <div style="text-align: center">{!! $problems->render() !!}</div>
                </div>
            @else
                Chưa có bài tập nào!
            @endif

        </div>
    </div>



@endsection