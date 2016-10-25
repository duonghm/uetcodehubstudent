@extends('layouts.app')

@section('extendedHead')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link href="{{URL::asset('assets/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet"
          type="text/css">
@endsection

@section('pageScript')
    <script src="{{URL::asset('assets/global/plugins/bootstrap-toastr/toastr.min.js')}}"
            type="text/javascript"></script>
@endsection

@section('script')
    <script type="text/javascript">
        $('#expand-button').click(function (e) {
            $('#editor-box').toggleClass('fullscreen');
            $('#problem-content').toggle();
        });
    </script>
    <script src="{{ URL::asset('js/ace-builds/src-min-noconflict/ace.js') }}" type="text/javascript"
            charset="utf-8"></script>
    <style>
        .readonly-highlight {
            /*background-color: gainsboro;*/
            background-color: grey;
            opacity: 0.2;
            position: absolute;
        }
    </style>
    <script>
        $('#language').change(function () {
            var lang = $(this).val();
            var editor = ace.edit("editor");
            switch (lang) {
                case "Java":
                    editor.getSession().setMode("ace/mode/java");
                    break;
                case "C++":
                    editor.getSession().setMode("ace/mode/c_cpp");
                    break;
                default:
                    editor.getSession().setMode("ace/mode/c_cpp");
            }
        });
    </script>
    <?php
    $templateCode = $problem->templateCode;
    if($templateCode == null){
        $templateCode = "''";
    } else{
        $templateCode = json_encode($templateCode);
    }


    ?>
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
	
    <script>
        String.prototype.replaceAll = function(search, replacement) {
            var target = this;
            return target.replace(new RegExp(search, 'g'), replacement);
        };
        var templateText = <?=$templateCode?>;
        var READONLY_MARK = "// readonly";

        // ACE Editor setting
        var editor = ace.edit("editor");
        var textarea = $('#source_code');
        editor.setTheme("ace/theme/chrome");
        editor.getSession().setMode("ace/mode/c_cpp");
        editor.getSession().on('change', function () {
            textarea.val(editor.getSession().getValue());
        });

        if(templateText.indexOf(READONLY_MARK) !== 1){
            editor.getSession().setValue(templateText.replaceAll(READONLY_MARK, ''));
        }else{
            editor.getSession().setValue(templateText);
        }


        textarea.val(editor.getSession().getValue());
        document.getElementById("editor").style.width = "100%"
        document.getElementById("editor").style.height = "400px";

        // Readonly code setting-----------------------

        var session = editor.getSession();
        Range = ace.require("ace/range").Range;

        var blockRanges = getReadonlyCode(templateText);
        for (var i = 0; i < blockRanges.length; i++) {
            var range = blockRanges[i];
            var markerId = session.addMarker(range, "readonly-highlight");
            range.start = session.doc.createAnchor(range.start);
            range.end = session.doc.createAnchor(range.end);
            range.end.$insertRight = true;
        }

        editor.keyBinding.addKeyboardHandler({
            handleKeyboard: function (data, hash, keyString, keyCode, event) {
                if ((keyCode <= 40 && keyCode >= 37)) return false;

                if (intersects(blockRanges)) {
                    return {command: "null", passEvent: false};
                }
            }
        });

        before(editor, 'onPaste', preventReadonly);
        before(editor, 'onCut', preventReadonly);

        function before(obj, method, wrapper) {
            var orig = obj[method];
            obj[method] = function () {
                var args = Array.prototype.slice.call(arguments);
                return wrapper.apply(this, function () {
                    return orig.apply(obj, orig);
                }, args);
            }

            return obj[method];
        }

        function intersects(blockRanges) {
            for (var i = 0; i < blockRanges.length; i++) {
                if (editor.getSelectionRange().intersects(blockRanges[i])) {
                    return true;
                }
            }
            return false;
        }

        function preventReadonly(next) {
            if (intersects(blockRanges)) return;
            next();
        }

        function getReadonlyCode(templateText){
            var blocks = [];
            var lines = templateText.split('\n');
            for(var i=0; i<lines.length; i++){
                if(lines[i].indexOf(READONLY_MARK) !== -1){
                    blocks.push(new Range(i,0,i+1,0));
                }
            }
            return blocks;
        }
    </script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $('#mytabs').tabs();
            $('#result').load('{{url(Request::path().'/submissionTable')}}');

            $(function () {
                function callAjax() {
                    console.log('{{url(Request::path().'/submissionTable')}}');
                    $('#result').load('{{url(Request::path().'/submissionTable')}}')
                }
                setInterval(callAjax, 5000);
            });

            $('#frmSubmit').submit(function () {
                var _sourceCode = $('#source_code').val();
                var _language = $('#language').val();
                $.ajax({
                    type: "POST",
                    url: "{{url('/submitExam')}}",
                    timeout: 5000,
                    data: {
                        sourceCode: _sourceCode,
                        language: _language,
                        examId: {{$examId}},
                        problemId: {{$problem->problemId}},
                        problemCode: '{{$problem->problemCode}}'
                    },
                    success: function (data) {
                        console.log(data);//
                        if (data == 'OK') {
                            //alert('submit OK');
                            $('#mytabs').tabs("option", "active", 1);
                            toastr.success("Submission notifications", "Your submission is sent successfully");
                        }else if(data == 'Timeout'){
                            toastr.warning("Submission notifications", "Your test was finished");
                        }else{
                            //alert('something wrong');
                            toastr.error("Submission notifications", "Error to submit submission");
                        }

                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log("Something error");
                        alert(xhr.status);
                        alert(ajaxOptions);
                        alert(thrownError);
                    }
                });
            });
        });
    </script>

    <script>
        function showSource(source) {
            $('#sourceText')[0].innerText = source;
        }
    </script>
@stop
@section('content')
    <div id="sourceModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Source Code</h4>
                </div>
                <!-- dialog body -->
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <pre id="sourceText"></pre>
                </div>
                <!-- dialog buttons -->
                <div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">OK</button></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div id="pl_pr" class="portlet light portlet-fit full-height-content full-height-content-scrollable ">
                <div class="portlet-title">

                    <div class="caption">
                        <i class=" icon-layers font-green"></i>
                        <span class="caption-subject font-green bold uppercase">
                            Mô tả bài toán
                        </span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="box" id="problem-content" style="min-height: 415px;">
                        <div style="background: #E0E0E0; margin-top: 10px; font-weight: bold">Problem statement</div>
                        <div class="box-content" style="text-align: justify; font-family: monospace;">
                            {!! $problem->content !!}
                        </div>
                        <div>
                            <div style="background: #E0E0E0; margin-top: 10px; font-weight: bold">Input Description
                            </div>
                            <div>{!! $problem->inputDescription !!}</div>
                        </div>
                        <div>
                            <div style="background: #E0E0E0; margin-top: 10px; font-weight: bold">Output Description
                            </div>
                            <div>{!! $problem->outputDescription !!}</div>
                        </div>
                        <div class="btn" style="position: absolute; bottom: 20px; text-align: right; width: 80%;">
                            <a href="{{ URL('/exams/'.$examId.'/') }}"> Back </a>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="col-md-9">
            <div class="portlet light portlet-fit full-height-content full-height-content-scrollable ">
                <div class="portlet-body">
                    <div style="float: right;font-family: inherit;font-weight: bold; color: cornflowerblue;">
                        <span id="countDownTimer">Loading timer...</span>
                    </div>
                    <div class="box">
                        <div id="mytabs" role="tabpanel">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class=""><a href="#editor-box" aria-controls="editor-box"
                                                                    role="tab"
                                                                    data-toggle="tab" aria-expanded="false">Mã nguồn</a>
                                </li>
                                <li role="presentation" class=""><a href="#result" aria-controls="submit" role="tab"
                                                                    data-toggle="tab" aria-expanded="false">Kết quả</a>
                                </li>
                            </ul>
                            <div class="tab-content ">
                                <div role="tabpanel"
                                     class="tab-pane {{Session::get('is_submitted') == true ? '' : 'active'}}"
                                     id="editor-box">

                                    <form id="frmSubmit" onsubmit="return false">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="panel">
                                            <div class="box">
                                                <div class="box-header">

                                                </div>
                                                <div class="box-content">
                                                    <div class="form-group" hidden>
                                                        <textarea class="form-control" name="source_code"
                                                                  id="source_code">

                                                        </textarea>
                                                    </div>
                                                    <div id="editor"></div>
                                                </div>
                                            </div>
                                            <div class="form-group" style="margin-top: 5px">
                                                <div class="pull-left" style="width:150px">
                                                    <select class="form-control" name="language" id="language"
                                                            onchange="changeLanguage()">
                                                        <option value="Cpp">C++</option>
                                                        <option value="C">C</option>
                                                        {{--<option value="Java">Java</option>--}}
                                                    </select>
                                                </div>
                                                <div>
                                                    <button class="btn btn-primary pull-right" type="submit"
                                                            id="submit-button">
                                                        Submit
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>

                                </div>
                                <div role="tabpanel"
                                     class="tab-pane {{Session::get('is_submitted') == true ? 'active' : ''}}"
                                     id="result">
                                    <div id="ajaxDemoContent">Demo content</div>
                                    {{--@include(url('/'))--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection