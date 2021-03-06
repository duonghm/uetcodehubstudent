<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Submission;
use Illuminate\Http\Request;
use Artisaninweb\SoapWrapper\Facades\SoapWrapper;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Mockery\CountValidator\Exception;

class JudgeController extends Controller
{
    var $submission;

    /*public function submit(Request $request, $course_id, $problem_id)
    {
        // Save submission to DB
        $submission = new Submission();
        $submission->problemId = $problem_id;
        $submission->courseId = $course_id;
        $submission->examId = null;
        $submission->userId = Auth::user()->userId;
        $submission->language = $request->input('language');
        $submission->sourceCode = $request->input('source_code');
        $submission->save();

        // Start judge service
        SoapWrapper::add(function ($service) {
            $service
                ->name('judge')
                ->wsdl('http://dumpcodehub.now-ip.org/JudgeServer/JudgeService?WSDL')
                ->trace(true)
                ->cache(WSDL_CACHE_NONE);
        });

        $data = [
            'problemId' => $submission->problemId,
            'sourceCode' => $submission->sourceCode,
            'language' => $submission->language,
            'limitTime' => $submission->problem->timeLimit,
            'limitMemory' => 0,
            'isUseCustomCheck' => false,
        ];

        try {
            SoapWrapper::service('judge', function ($service) use ($data, $submission) {
                $result = $service->call('judge', [$data])->return;
                $decoded = json_decode($result, true);
                if (array_key_exists('score', $decoded)) {
                    $submission->resultScore = intval($decoded['score']);
                }
                $submission->result = $result;
                $submission->save();
            });
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        session()->flash('is_submitted', true);
        return Redirect::back();
    }*/

    /*public function submitExam(Request $request, $exam_id, $problem_id)
    {
        // Save submission to DB
        $submission = new Submission();
        $submission->problemId = $problem_id;
        $submission->courseId = null;
        $submission->examId = $exam_id;
        $submission->userId = Auth::user()->userId;
        $submission->language = $request->input('language');
        $submission->sourceCode = $request->input('source_code');
        $submission->save();

        // Start judge service
        SoapWrapper::add(function ($service) {
            $service
                ->name('judge')
                ->wsdl('http://codehub.now-ip.org/JudgeServer/JudgeService?WSDL')
                ->trace(true)
                ->cache(WSDL_CACHE_NONE);
        });

        $data = [
            'problemId' => $submission->problemId,
            'sourceCode' => $submission->sourceCode,
            'language' => $submission->language,
            'limitTime' => $submission->problem->timeLimit,
            'limitMemory' => 0,
            'isUseCustomCheck' => false,
        ];

        try {
            SoapWrapper::service('judge', function ($service) use ($data, $submission) {
                $result = $service->call('judge', [$data])->return;
                $decoded = json_decode($result, true);
                if (array_key_exists('score', $decoded)) {
                    $submission->resultScore = intval($decoded['score']);
                }
                $submission->result = $result;
                $submission->save();
            });
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        session()->flash('is_submitted', true);
        return Redirect::back();
    }*/

    public function submitAjax(Request $request)
    {

        try {
            // Save submission to DB
            $this->submission = new Submission();
            $this->submission->problemId = $request->input('problemId');
            $this->submission->courseId = $request->input('courseId');
            $this->submission->examId = null;
            $this->submission->userId = Auth::user()->userId;
            $this->submission->language = $request->input('language');
            $this->submission->sourceCode = $request->input('sourceCode');
            $this->submission->save();

            $client = new \SoapClient("http://localhost:8080/CodehubJudgeAssistant/SubmitService?wsdl", array('cache_wsdl' => WSDL_CACHE_NONE));
            $submitData = new \stdClass();
            $submitData->userId = $this->submission->userId;
            $submitData->submitId = $this->submission->submitId;
            $submitData->problemCode = $request->input('problemCode');
            $submitData->sourceCode = $this->submission->sourceCode;
            $submitData->language = $this->submission->language;
            $submitData->limitTime = $this->submission->problem->timelimit;
            $submitData->limitMemory = 0;
            $submitData->isUseCustomCheck = false;
            $client->submit($submitData);

            return 'OK';
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            return 'Error';
        }
    }

    public function submitExam(Request $request)
    {

        if ($this->isValidateExamSubmissionTime($request->input('examId'))) {
            try {
                // Save submission to DB
                $this->submission = new Submission();
                $this->submission->problemId = $request->input('problemId');
                $this->submission->courseId = null;
                $this->submission->examId = $request->input('examId');
                $this->submission->userId = Auth::user()->userId;
                $this->submission->language = $request->input('language');
                $this->submission->sourceCode = $request->input('sourceCode');

                $this->submission->save();

                $client = new \SoapClient("http://localhost:8080/CodehubJudgeAssistant/SubmitService?wsdl", array('cache_wsdl' => WSDL_CACHE_NONE));
                //$client = new \SoapClient("http://171.234.220.65:8080/CodehubJudgeAssistant/SubmitService?wsdl", array('cache_wsdl' => WSDL_CACHE_NONE));
                //$client = new \SoapClient("http://117.5.108.104:8080/CodehubJudgeAssistant/SubmitService?wsdl", array('cache_wsdl' => WSDL_CACHE_NONE));
                $submitData = new \stdClass();
                $submitData->userId = $this->submission->userId;
                $submitData->submitId = $this->submission->submitId;
                $submitData->problemCode = $request->input('problemCode');
                $submitData->sourceCode = $this->submission->sourceCode;
                $submitData->language = $this->submission->language;
                $submitData->limitTime = $this->submission->problem->timelimit;
                $submitData->limitMemory = 0;
                $submitData->isUseCustomCheck = false;
                $client->submit($submitData);

                return 'OK';
            } catch (\Exception $ex) {
                echo $ex->getMessage();
                return 'Error';
            }
        }else{
            return 'Timeout';
        }

    }

    private function isValidateExamSubmissionTime($examId)
    {
        $exam = Exam::find($examId);
//        $now = strtotime((new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh')))->format('Y-m-d H:i:s'));
        $now = strtotime((new \DateTime('now'))->format('Y-m-d H:i:s'));
        $joinTime = strtotime($exam->joinTime(Auth::user()->userId));
        $time = $now - $joinTime;
        $remainTime = $exam->duration * 60 - $time;
        return $remainTime > 0;
    }

    /*function saveSubmission(Request $request){
        try{
            // Save submission to DB
            $this->submission = new Submission();
            $this->submission->problemId = $request->input('problemId');
            $this->submission->courseId = $request->input('courseId');
            $this->submission->examId = null;
            $this->submission->userId = Auth::user()->userId;
            $this->submission->language = $request->input('language');
            $this->submission->sourceCode = $request->input('sourceCode');
            //while(1);
            //$a = 1/0;
            $this->submission->save();
            return 'OK';
        }catch(\Exception $ex){
            return 'Error';
        }
    }*/

    /*public function callJudge(){
        SoapWrapper::add(function ($service) {
            $service
                ->name('judge')
                ->wsdl('http://192.168.40.128:8080/JudgeServer/JudgeService?WSDL')
                ->trace(true)
                ->cache(WSDL_CACHE_NONE);
        });

        $data = [
            'problemId' => $this->submission->problemId,
            'sourceCode' => $this->submission->sourceCode,
            'language' => $this->submission->language,
            'limitTime' => $this->submission->problem->timeLimit,
            'limitMemory' => 0,
            'isUseCustomCheck' => false,
        ];

        SoapWrapper::service('judge', function ($service) use ($data) {
            $result = $service->call('judge', [$data])->return;
            $decoded = json_decode($result, true);
            if (array_key_exists('score', $decoded)) {
                $this->submission->resultScore = intval($decoded['score']);
            }
            $this->submission->result = $result;
            $this->submission->save();
        });
    }*/
}
