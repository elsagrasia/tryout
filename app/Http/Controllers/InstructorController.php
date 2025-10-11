<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class InstructorController extends Controller
{
    public function instructorDashboard()
    {
        return view('instructor.index');
    } //end method

    public function instructorLogout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'info'
        );

        return redirect('/instructor/login')->with($notification);
    } //end method

    public function instructorLogin() {
        return view('instructor.instructor_login');
    } //end method

    public function instructorProfile() {
        
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('instructor.instructor_profile_view', compact('profileData'));
    } //end method

    public function instructorProfileStore(Request $request) {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        $profileData->name = $request->name;
        $profileData->username = $request->username;
        $profileData->email = $request->email;
        $profileData->phone = $request->phone;
        $profileData->address = $request->address;

        if($request->hasFile('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/instructor_images/' . $profileData->photo));
            $extension = $file->getClientOriginalExtension();
            $filename = date('YmdHi') . '.' . $extension;
            $file->move('upload/instructor_images/', $filename);
            $profileData->photo = $filename;
        } //end if
       
        $profileData->save();

        $notification = array(
            'message' => 'Instructor Profile Updated Successfully',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    } //end method


    public function instructorChangePassword() {
        
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('instructor.instructor_change_password', compact('profileData'));
    } //end method

    public function instructorPasswordUpdate(Request $request) {

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

}
