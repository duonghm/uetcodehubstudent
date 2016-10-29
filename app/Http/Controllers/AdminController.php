<?php
/**
 * Created by PhpStorm.
 * User: ngxson
 * Date: 10/29/2016
 * Time: 4:31 PM
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

class AdminController extends Controller{
    
    public function editProblem($courseId, $problemId){
        return view('admin.editProblem', compact('courseId', 'problemId'));
    }
	
	public function addProblem(){
		$courseId = 0;
		$problemId = 0;
        return view('admin.editProblem', compact('courseId', 'problemId'));
    }
    
}
