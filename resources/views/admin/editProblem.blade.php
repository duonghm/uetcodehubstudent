@extends('layouts.app')

@section('extendedHead')
@endsection

@section('pageScript')
@endsection

@section('script')
@endsection

@section('pageTitle')
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div id="pl_pr" class="portlet light portlet-fit full-height-content full-height-content-scrollable">
            <div class="portlet-title">
                <div class="caption" style="width:100%;">
                    <span class="caption-subject font-blue bold uppercase">
                        NEW PROBLEM:<br/>
						<div style="text-align: justify; font-family: monospace;">
							<textarea id="problemCode" style="height:32px; width:100%;"></textarea>
						</div>
                    </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="box" id="problem-content" style="min-height: 420px;">
                    <div style="background: #E0E0E0; margin-top: 10px; font-weight: bold">Problem statement</div>
                    <div class="box-content" style="text-align: justify; font-family: monospace;">
						<textarea id="problemContent" style="height:350px; width:100%;"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="col-md-6">
        <div id="pl_pr" class="portlet light portlet-fit full-height-content full-height-content-scrollable">
            <div class="portlet-body">
                <div class="box" id="problem-content" style="min-height: 600px;">
                    <div style="text-align: justify; font-family: monospace;">
                        <div style="background: #E0E0E0; margin-top: 10px; font-weight: bold;">Input Description
                        </div>
                        <textarea id="problemInput" style="height:120px; width:100%;">&lt;code&gt;&lt;/code&gt;</textarea>
                    </div>
                    <div style="text-align: justify; font-family: monospace;">
                        <div style="background: #E0E0E0; margin-top: 10px; font-weight: bold;">Output Description
                        </div>
                        <textarea id="problemOutput" style="height:120px; width:100%;">&lt;code&gt;&lt;/code&gt;</textarea>
                    </div>
					<br/>
					Course:
					<select id="selectCourse" style="width:100%;">
						<option value="11">option11</option>
						<option value="9">option9</option>
					</select><br/><br/>
					Hard level: <input type="text" value="1"/><br/>
					Score in course: <input type="text" value="0"/><br/>
					Total score: <input type="text" value="100"/><br/>
					Is Active: <input type="text" value="1"/><br/>
					<br/>
					<br/>
					<button class="btn btn-primary pull-right" id="submit-button">
                        DONE
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
