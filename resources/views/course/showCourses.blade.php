@extends('layouts.app')

{{--@section('pageTitle')--}}
    {{--My courses--}}
{{--@endsection--}}

@section('content')

    <div class="portlet light portlet-fit full-height-content full-height-content-scrollable ">
        <div class="portlet-title">
            <div class="caption">
                <i class=" icon-layers font-green"></i>
                <span class="caption-subject font-green bold uppercase">Khóa học của tôi</span>
            </div>
        </div>
        <div class="portlet-body">
            @if(sizeof($courses) > 0)
                <div class="table-scrollable table-scrollable-borderless">
                    <table class="table table-hover table-light">
                        <thead>
                        <tr>
                            <th>Tên khóa học</th>
                            <th>Giảng viên</th>
                            <th>Mô tả</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($courses as $course)
                            <tr>
                                <td>
                                    <a href="{{url('/my-courses/'.$course->courseId.'/problems')}}"> {{$course->courseName}} </a>
                                </td>
                                <td> {{$course->lecturer}} </td>
                                <td> {{$course->description}} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div>
                    Bạn chưa tham gia lớp học nào!
                </div>
            @endif
        </div>
    </div>





@endsection