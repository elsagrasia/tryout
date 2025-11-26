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
            'message' => 'Profil Pengguna Berhasil Diperbarui',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } //end method
    
    public function userLogout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Anda berhasil keluar.',
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
        ],
        [
            'old_password.required' => 'Masukan Kata Sandi Lama',
            'new_password.required' => 'Masukan Kata Sandi Baru',
        ]);
        if (!Hash::check($request->old_password, auth::user()->password)) {
            $notification = array(
                'message' => 'Kata Sandi Lama Tidak Cocok',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
        /// update new password
        User::whereId(auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
        $notification = array(
            'message' => 'Kata Sandi Berhasil Diubah',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } //end method

    public function liveChat(){ 
        return view('frontend.dashboard.live_chat'); 
    } // End Method 



}
