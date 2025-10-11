<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role; 
use Spatie\Permission\Models\Permission;
use App\Exports\PermissionExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PermissionImport;
use App\Models\User;
use DB;

class RoleController extends Controller
{
    public function allPermission(){

        $permissions = Permission::all();
        return view('admin.backend.pages.permission.all_permission', compact('permissions'));
    } //end method

    public function addPermission(){

        return view('admin.backend.pages.permission.add_permission');

    }// End Method 

    public function storePermission(Request $request){

        Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission Created Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.permission')->with($notification);  

    }// End Method 
    
  public function editPermission($id){

        $permission = Permission::find($id);
        return view('admin.backend.pages.permission.edit_permission',compact('permission'));

    }// End Method 

    public function updatePermission(Request $request){

        $per_id = $request->id;

        Permission::find($per_id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.permission')->with($notification);  

    }// End Method 

    public function deletePermission($id){

        Permission::find($id)->delete();

        $notification = array(
            'message' => 'Permission Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);  

    }// End Method 

    public function importPermission(){

        return view('admin.backend.pages.permission.import_permission');

    }// End Method     

    public function export(){

        return Excel::download(new PermissionExport, 'permission.xlsx');

    }// End Method

    public function import(Request $request){

        Excel::import(new PermissionImport, $request->file('import_file'));

        $notification = array(
            'message' => 'Permission Imported Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);  

    }// End Method
    

      ////////// ALL ROLE METHODS /////////


    public function allRoles(){

        $roles = Role::all();
        return view('admin.backend.pages.roles.all_roles',compact('roles'));

    }// End Method

    public function addRoles(){

        return view('admin.backend.pages.roles.add_roles');

    }// End Method

    public function storeRoles(Request $request){

        Role::create([
            'name' => $request->name, 
        ]);

        $notification = array(
            'message' => 'Role Created Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles')->with($notification);  

    }// End Method     

   public function editRoles($id){

        $roles = Role::find($id);
        return view('admin.backend.pages.roles.edit_roles',compact('roles'));


    }// End Method 

    public function updateRoles(Request $request){

        $role_id = $request->id;

        Role::find($role_id)->update([
            'name' => $request->name, 
        ]);

        $notification = array(
            'message' => 'Role Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles')->with($notification);  

    }// End Method 

    public function deleteRoles($id){

        Role::find($id)->delete();

        $notification = array(
            'message' => 'Role Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 

    }// End Method 

  //////////// Add Role Permission All Mehtod ////////////////

    public function addRolesPermission(){


        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();

        return view('admin.backend.pages.rolesetup.add_roles_permission',compact('roles','permissions','permission_groups'));

    }// End Method 

    public function rolePermissionStore(Request $request){

        $data = array();
        $permissions = $request->permission;

        foreach ($permissions as $key => $item) {
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;

            DB::table('role_has_permissions')->insert($data);
        } // end foreach


        $notification = array(
            'message' => 'Role Permission Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles.permission')->with($notification); 


    }// End Method 

    public function allRolesPermission(){

        $roles = Role::all();
        return view('admin.backend.pages.rolesetup.all_roles_permission',compact('roles'));

    }// End Method 

    public function adminEditRoles($id){

        $role = Role::find($id);
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();

        return view('admin.backend.pages.rolesetup.edit_roles_permission',compact('role','permission_groups','permissions'));


    }// End Method   
    
    public function adminUpdateRoles(Request $request, $id)
    {
        $role = Role::find($id);

        if (!empty($request->permission)) {
            // Convert ID -> name (agar syncPermissions bisa jalan)
            $permissionNames = Permission::whereIn('id', $request->permission)->pluck('name')->toArray();
            $role->syncPermissions($permissionNames);
        }

        $notification = [
            'message' => 'Role Permission Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.roles.permission')->with($notification);
    }   // End Method

    public function adminDeleteRoles($id){

            $role = Role::find($id);
            if (!is_null($role)) {
                $role->delete();
            }

            $notification = array(
                'message' => 'Role Permission Deleted Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);

        }// End Method 

}
