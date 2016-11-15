var courseId = -1;
var examId = -1;
var problemId;
var problemCode;
var submitUrl;
var submissionTableUrl;

/** Setup submitting method
 * @param {String} url					Submit POST URL
 * @param {Integer} cid					courseId; -1 to disable
 * @param {Integer} eid					examId; -1 to disable
 * @param {Integer} pid					problemId
 * @param {String} pc					problemCode
 */
function setupSubmitProblem(url, cid, eid, pid, pc) {
	submitUrl = url;
	courseId = cid;
	examId = eid;
	problemId = pid;
	problemCode = pc;
}

/** Setup submission table URL
 * @param {String} url					submission table URL
 */
function setupSubmissionTable(url) {
	submissionTableUrl = url;
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function reloadSubmissionTable() {
	$('#result').load(submissionTableUrl);
}

$(document).ready(function () {
	var ajaxGetTimes = 0;
	var blockSubmitBtn = false;
    $('#mytabs').tabs();
    $('#result').load(submissionTableUrl);

    function refreshSubmissionTable() {
		ajaxGetTimes++;
		if (ajaxGetTimes < 6) {
			//console.log(ajaxGetTimes+'{{url(Request::path().'/submissionTable')}}');
			$('#result').load(submissionTableUrl);
			setTimeout(refreshSubmissionTable, 5000);
		}
    }
	
	function getSubmissionTable() {
		if (ajaxGetTimes == 0) {
			refreshSubmissionTable();
		} else {
			ajaxGetTimes = 0;
		}
	}
	
	function unblockSubmitBtn() {
		blockSubmitBtn = false;
		$("#submit-button").html("SUBMIT");
	}

    $('#frmSubmit').submit(function () {
		if (!blockSubmitBtn) {
			blockSubmitBtn = true;
			//setTimeout(unblockSubmitBtn, 5000);
			$("#submit-button").html("<img height=\"14\" width=\"14\" src=\"/assets/layouts/layout/img/loading_submit.gif\" /> LOADING...");
			
			var _sourceCode = $('#source_code').val();
			var _language = $('#language').val();
			if (_sourceCode == "" || _sourceCode === undefined) {
				unblockSubmitBtn();
				toastr.warning("Cannot submit empty code", "Error");
			} else {
				if (courseId > -1) submitProblemHandler(_sourceCode, _language);
				else submitExamHandler(_sourceCode, _language);
			}
		}
    });
	
	function submitProblemHandler(_sourceCode, _language) {
		$.ajax({
			type: "POST",
			url: submitUrl,
			timeout: 5000,
			data: {
				sourceCode: _sourceCode,
				language: _language,
				courseId: courseId,
				problemId: problemId,
				problemCode: problemCode
			},
			success: function (data) {
				unblockSubmitBtn();
				console.log(data);//
				if (data == 'OK') {
					//alert('submit OK');
					$('#mytabs').tabs("option", "active", 1);
					getSubmissionTable();
					toastr.success("Submission notifications", "Your submission is sent successfully");
				} else {
					//alert('something wrong');
					getSubmissionTable();
					toastr.error("Submission notifications", "Error to submit submission");
				}
	
			},
			error: function (xhr, ajaxOptions, thrownError) {
				unblockSubmitBtn();
				alert(xhr.status);
				alert(ajaxOptions);
				alert(thrownError);
			}
		});
	}
	
	function submitExamHandler(_sourceCode, _language) {
		$.ajax({
			type: "POST",
			url: submitUrl,
			timeout: 5000,
			data: {
				sourceCode: _sourceCode,
				language: _language,
				examId: examId,
				problemId: problemId,
				problemCode: problemCode
			},
			success: function (data) {
				unblockSubmitBtn();
				console.log(data);//
				if (data == 'OK') {
					//alert('submit OK');
					$('#mytabs').tabs("option", "active", 1);
					getSubmissionTable();
					toastr.success("Submission notifications", "Your submission is sent successfully");
				} else {
					//alert('something wrong');
					getSubmissionTable();
					toastr.error("Submission notifications", "Error to submit submission");
				}
	
			},
			error: function (xhr, ajaxOptions, thrownError) {
				unblockSubmitBtn();
				alert(xhr.status);
				alert(ajaxOptions);
				alert(thrownError);
			}
		});
	}
});