<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Course;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function adminDashboard()
    {
        // Hitung total pengguna (semua user)
        $totalUsers = User::count();

        // Hitung total instructor
        $totalInstructors = User::where('role', 'instructor')->count();

        return view('admin.index', compact('totalUsers', 'totalInstructors'));
        // return view('admin.index');


    } //end method

    public function adminLogout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Anda berhasil keluar.',
            'alert-type' => 'info'
        );
 
        return redirect('/admin/login')->with($notification);
    } //end method

    public function adminLogin() {
        return view('admin.admin_login');
    } //end method

    public function adminProfile() {
        
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_profile_view', compact('profileData'));
    } //end method

    public function adminProfileStore(Request $request) {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        $profileData->name = $request->name;
        $profileData->username = $request->username;
        $profileData->email = $request->email;
        $profileData->phone = $request->phone;
        $profileData->address = $request->address;

        if($request->hasFile('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/' . $profileData->photo));
            $extension = $file->getClientOriginalExtension();
            $filename = date('YmdHi') . '.' . $extension;
            $file->move('upload/admin_images/', $filename);
            $profileData->photo = $filename;
        } //end if
       
        $profileData->save();

        $notification = array(
            'message' => 'Profil Admin Berhasil Diperbarui',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    } //end method

    public function adminChangePassword() {
        
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_change_password', compact('profileData'));
    } //end method

    public function adminPasswordUpdate(Request $request) {

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
            'message' => 'Password Admin Berhasil Diubah',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } //end method
   

}
