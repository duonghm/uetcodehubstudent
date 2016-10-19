<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //if(Auth::guest()){
            $statistic = new \App\CustomClass\Statistic;
            return view('welcome', compact('statistic'));
        //}else{
        //  return view("user.user");
        //}
    }

    public function user(){
        $statistic = new \App\CustomClass\Statistic;
        return view("user.user", compact('statistic'));
    }

    public function changePassword(Request $request){
        $currentPass = $request->input('currentPass');
        $newPass = $request->input('newPass');
        if(md5($currentPass)==Auth::user()->password){
            try{
                $user = User::find(Auth::user()->userId);
                $user->timestamps = false;
                $user->password = md5($newPass);
                $user->save();
                return "OK";
            }catch (\Exception $ex){
                return $ex;
            }

        }else{
            return "ERR_AUTH";
        }

    }

}
