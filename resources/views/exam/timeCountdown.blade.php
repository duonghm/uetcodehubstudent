@if($remainTime < 0)
    <span style="color: #e7505a">Hết giờ làm bài </span>
@else
    @php
        $minute = floor($remainTime/60);
        $second = $remainTime%60;
    @endphp
    @if($remainTime > 300)
        <span style="color: cornflowerblue">Thời gian còn lại: {{$minute.'m'.$second.'s'}}</span>
    @else
        <span style="color: #df8505">Thời gian còn lại: {{$minute.'m'.$second.'s'}}</span>
    @endif
@endif


