@if (sizeof($submissions))
    <div class="portlet box red">
        <div class="portlet-title">
            <div class="caption">All Result</div>
			<script>
				setTimeout(function() {
					document.getElementById("refreshSubmission").style.display="inline";
				}, 6000);
			</script>
			<button class="btn btn-danger pull-right" type="submit" id="refreshSubmission"
				onclick="reloadSubmissionTable();" style="display: none;">
                REFRESH
            </button>
        </div>
        <div class="portlet-body">
            <div class="table-scrollable">
                <div class="table-scrollable">


                    <table class="table table-condensed table-hover" id="tblResult">
                        <thead>
                        <tr>
                            <td>#</td>
                            <td>Score</td>
                            <td>Message</td>
                            <td>Status</td>
                        </tr>
                        </thead>
                        @foreach($submissions as $submission)
                            <?php $resultDetail = json_decode($submission->result, true) ?>
                            <tr>
                                <td style="width: 10%">
                                    {{$submission->submitId}}
                                </td>
                                @if($resultDetail['resultCode'] === 'AC')
                                    <td style="width: 10%">{{$resultDetail['score']}}</td>
                                    <td style="width: 50%">
                                        <?php $testDetail = $resultDetail['testDetail'] ?>
                                        @foreach($testDetail as $tc)
                                            {{$tc['testName']}} - {{$tc['result']}} - {{$tc['message']}}
                                            @if( isset($tc['time']) && $tc['time'] != 'null' )
                                                - {{$tc['time']}} ms
                                            @endif
                                            @if( isset($tc['memory']) && $tc['memory'] != 'null' )
                                                - {{$tc['memory']}} kbytes
                                            @endif
                                            <br/>
                                        @endforeach
                                    </td>
                                    <td style="width: 15%">
                                        <span class="label label-sm label-success">Accept</span>
                                    </td>
                                @elseif(!$resultDetail['resultCode'])
                                    <td style="width: 10%">-</td>
                                    <td>-</td>
                                    <td>
                                        <span class="label label-sm label-info">Pending</span>
                                    </td>
                                @else
                                    <td>0</td>
                                    <td>{{$resultDetail['message']}}</td>
                                    <td>
                                        <span class="label label-sm label-danger">Fail</span>
                                    </td>
                                @endif
                                <td>
                                    <?php
                                    $source = $submission->sourceCode != null ? json_encode($submission->sourceCode) : '';
                                    ?>
                                    {{--<span class="btn btn-primary" onclick="alert('{{$source}}')">Get source</span>--}}
                                    <span class="btn btn-primary" data-toggle="modal" data-target="#sourceModal"
                                          onclick="showSource({{$source}})">Get source</span>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-danger">
        <strong>Bạn chưa nộp bài!</strong>
    </div>
@endif