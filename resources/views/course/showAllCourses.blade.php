@extends('layouts.app')

{{--@section('pageTitle')--}}
    {{--All courses--}}
{{--@endsection--}}

@section('content')

    <div class="portlet light portlet-fit full-height-content full-height-content-scrollable ">
        <div class="portlet-title">
            <div class="caption">
                <i class=" icon-layers font-green"></i>
                <span class="caption-subject font-green bold uppercase">All Courses</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-scrollable table-scrollable-borderless">
                <table class="table table-hover table-light">
                    <thead>
                    <tr>
                        <th>Tên khóa học</th>
                        <th>Giảng viên</th>
                        <th>Kì học</th>
                        <th>Trạng thái</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <td>
                                {{$course->courseName}}
                                {{--<a data-toggle="modal" data-target="#course-info-data">{{$course->courseName}}</a>--}}
                                {{--<div class="modal fade" id="course-info-data" role="dialog">--}}
                                    {{--<div class="modal-dialog">--}}
                                        {{--<div class="modal-content">--}}
                                            {{--<div class="modal-header">--}}
                                                {{--<button type="button" class="close"--}}
                                                        {{--data-dismiss="modal">&times;</button>--}}
                                                {{--<h4 class="modal-title">Thông tin khóa học</h4>--}}
                                            {{--</div>--}}
                                            {{--<div class="modal-body">--}}
                                                {{--<p>{{$course->description}}</p>--}}
                                            {{--</div>--}}
                                            {{--<div class="modal-footer">--}}
                                                {{--<button type="button" class="btn btn-default" data-dismiss="modal">--}}
                                                    {{--Đóng--}}
                                                {{--</button>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            </td>
                            <td>{{$course->lecturer}}</td>
                            <td>{{$course->semester->semesterName}}</td>
                            @if (!$course->joined)
                                <td>
                                    <a data-toggle="modal" data-target="#enroll-modal-{{$course->courseId}}">Tham gia</a>
                                    <div class="modal fade" id="enroll-modal-{{$course->courseId}}" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close"
                                                            data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Xác nhận tham gia</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Bạn muốn tham gia lớp {{$course->courseName}}?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    {!! Form::open([
                                                        'action' => array('CourseController@joinCourse', $course->courseId),
                                                        'class' => 'form-horizontal',
                                                        'method' => 'post',
                                                    ]) !!}
                                                    <div class="form-group">
                                                        <div>
                                                            <button type="submit" class="btn btn-primary">
                                                                Tham gia lớp
                                                            </button>
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">
                                                                Hủy bỏ
                                                            </button>

                                                        </div>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @else
                                <td>
                                    Đã tham gia
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>



@endsection