<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function allCategory(){
        $id = Auth::user()->id;
        $category = Category::latest()->get();
        return view('instructor.category.all_category',compact('category'));
    } //end method

    public function storeCategory(Request $request){
        $id = Auth::user()->id;
        Category::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
            // 'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
        ]);
        $notification = array(
            'message' => 'Kategori Berhasil Ditambahkan',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } //end method

    public function editCategory($id){
        $category = Category::findOrFail($id);
        return response()->json($category);
    } //end method

    public function updateCategory(Request $request){
        $cat_id = $request->cat_id;

        Category::findOrFail($cat_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
        ]);
        $notification = array(
            'message' => 'Kategori Berhasil Diperbarui',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } //end method
    
    public function deleteCategory($id){
        Category::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Kategori Berhasil Dihapus',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } //end method
  
}
