<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ActiveUserController extends Controller
{
    //
    public function allUser(){
        $users = User::where('role','student')->latest()->get();
        return view('admin.backend.user.user_all',compact('users'));

    }// End Method 


    public function allInstructor(){
        $users = User::where('role','instructor')->latest()->get();
        return view('admin.backend.user.instructor_all',compact('users'));

    }// End Method 


}
