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
            'message' => 'Logout Berhasil',
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

    public function becomeInstructor() {
        return view('frontend.instructor.reg_instructor');
    } //end method

    public function instructorRegister(Request $request) {
        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','string','unique:users'],
        ]);

        User::insert([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'instructor',
            'status' => '0',
        ]);

        $notification = array(
            'message' => 'Instruktur Berhasil Terdaftar',
            'alert-type' => 'success'
        );
        return redirect()->route('instructor.login')->with($notification);
    } //end method

    public function allInstructor() {
        $allinstructor = User::where('role', 'instructor')->latest()->get();
        return view('admin.backend.instructor.all_instructor', compact('allinstructor'));
    } //end method

    public function updateUserStatus(Request $request) {
        $userId = $request->input('user_id');
        $isChecked = $request->input('is_checked',0);
        
        $user = User::find($userId);
        if ($user) {
            $user->status = $isChecked;
            $user->save();
        }

        return response()->json(['message' => 'Status Pengguna berhasil diperbarui.']);
    } //end method

 

  /// Admin User All Method ////////////

    public function allAdmin(){

        $alladmin = User::where('role','admin')->get();
        return view('admin.backend.pages.admin.all_admin',compact('alladmin'));

    }// End Method

   public function addAdmin(){

        $roles = Role::all();
        return view('admin.backend.pages.admin.add_admin',compact('roles'));

    }// End Method

    public function storeAdmin(Request $request){

        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = '1';
        $user->save();

        if ($request->roles) {
            $role = Role::find($request->roles);   // cari role berdasarkan ID
            if ($role) {
                $user->assignRole($role->name);   // pakai nama role
            }
        }

        $notification = array(
            'message' => 'Admin Baru Berhasil Ditambahkan',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification); 

    }// End Method    
    public function editAdmin($id){

        $user = User::find($id);
        $roles = Role::all();
        return view('admin.backend.pages.admin.edit_admin',compact('user','roles'));

    }// End Method

    public function updateAdmin(Request $request,$id){

        $user = User::find($id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address; 
        $user->role = 'admin';
        $user->status = '1';
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $role = Role::find($request->roles);
            if ($role) {
                $user->assignRole($role->name);
            }
        }

        $notification = array(
            'message' => 'Admin Berhasil Diperbarui',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification); 

    }// End Method


    public function deleteAdmin($id){

        $user = User::find($id);
        if (!is_null($user)) {
            $user->delete();
        }

        $notification = array(
            'message' => 'Admin Berhasil Dihapus',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 

    }// End Method    
}
