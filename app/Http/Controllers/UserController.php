<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index()
    {
        return view('frontend.index');
    }

    public function userProfile()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('frontend.dashboard.edit_profile', compact('profileData'));
    }

    public function userProfileUpdate(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images/' . $data->photo));
            $filename = date('YmdHi') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/user_images'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();
        $notification = array(
            'message' => 'User Profile Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } //end method
    
    public function userLogout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'info'
        );

        return redirect('/login')->with($notification);
    } //end method

    public function userChangePassword()
    {
        return view('frontend.dashboard.change_password');
    } //end method

    public function userPasswordUpdate(Request $request) {

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);
        if (!Hash::check($request->old_password, auth::user()->password)) {
            $notification = array(
                'message' => 'Old Password Not Matched',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
        /// update new password
        User::whereId(auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
        $notification = array(
            'message' => 'Password Change Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } //end method

    public function liveChat(){ 
        return view('frontend.dashboard.live_chat'); 
    } // End Method 



}
