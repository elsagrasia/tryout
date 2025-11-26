<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;

use App\Models\Point;
use App\Models\UserPoint;
use Illuminate\Support\Facades\Auth;

use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BlogController extends Controller
{
   public function allBlogCategory(){

    $category = BlogCategory::latest()->get();
    return view('admin.backend.blogcategory.blog_category',compact('category'));

   }// End Method 

   public function storeBlogCategory(Request $request){

    BlogCategory::insert([
        'category_name' => $request->category_name,
        'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
    ]);

    $notification = array(
        'message' => 'Kategori Blog Berhasil Ditambahkan',   
        'alert-type' => 'success'
    );
    return redirect()->back()->with($notification);


   }// End Method    

   public function editBlogCategory($id){

    $categories = BlogCategory::find($id);
    return response()->json($categories);

   }// End Method    

  public function updateBlogCategory(Request $request){
    $cat_id = $request->cat_id;

    BlogCategory::find($cat_id)->update([
        'category_name' => $request->category_name,
        'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
    ]);

    $notification = array(
        'message' => 'Kategori Blog Berhasil Diperbarui',
        'alert-type' => 'success'
    );
    return redirect()->back()->with($notification);


   }// End Method 

   public function deleteBlogCategory($id){
   
    BlogCategory::find($id)->delete();

    $notification = array(
        'message' => 'Kategori Blog Berhasil Dihapus',
        'alert-type' => 'success'
    );
    return redirect()->back()->with($notification);


   }// End Method    

   //////////// All Blog Post Method .//

   public function blogPost(){
    $post = BlogPost::latest()->get();
    return view('admin.backend.post.all_post',compact('post'));
   }// End Method 

   public function addBlogPost(){

    $blogcat = BlogCategory::latest()->get();
    return view('admin.backend.post.add_post',compact('blogcat'));

   }// End Method 

     public function storeBlogPost(Request $request){

    $image = $request->file('post_image');  
    $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
    Image::make($image)->resize(370,247)->save('upload/post/'.$name_gen);
    $save_url = 'upload/post/'.$name_gen;

    BlogPost::insert([
        'blogcat_id' => $request->blogcat_id,
        'post_title' => $request->post_title,
        'post_slug' => Str::slug($request->post_title),

        'long_descp' => $request->long_descp,
        
        'post_image' => $save_url,  
        'created_at' => Carbon::now(),      

    ]);

    $notification = array(
        'message' => 'Blog Post Berhasil Ditambahkan',
        'alert-type' => 'success'
    );
    return redirect()->route('blog.post')->with($notification);  

   }// End Method   

 public function editBlogPost($id){

    $blogcat = BlogCategory::latest()->get();
    $post = BlogPost::find($id);
    return view('admin.backend.post.edit_post',compact('post','blogcat'));

   }// End Method 


   public function updateBlogPost(Request $request){
        
    $post_id = $request->id;

    if ($request->file('post_image')) {

        $image = $request->file('post_image');  
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,247)->save('upload/post/'.$name_gen);
        $save_url = 'upload/post/'.$name_gen;
    
        BlogPost::find($post_id)->update([
            'blogcat_id' => $request->blogcat_id,
            'post_title' => $request->post_title,
            'post_slug' => Str::slug($request->post_title),
            'long_descp' => $request->long_descp,           
            'post_image' => $save_url,  
            'created_at' => Carbon::now(),      
    
        ]);
    
        $notification = array(
            'message' => 'Blog Post Berhasil Diperbarui',
            'alert-type' => 'success'
        );
        return redirect()->route('blog.post')->with($notification);  
    
    } else {

        BlogPost::find($post_id)->update([
            'blogcat_id' => $request->blogcat_id,
            'post_title' => $request->post_title,
            'post_slug' => Str::slug($request->post_title),
            'long_descp' => $request->long_descp, 
            'created_at' => Carbon::now(),      
    
        ]);
    
        $notification = array(
            'message' => 'Blog Post Berhasil Diperbarui',
            'alert-type' => 'success'
        );
        return redirect()->route('blog.post')->with($notification);  

    } // end else 

    }// End Method    

    public function deleteBlogPost($id){

        $item = BlogPost::find($id);
        $img = $item->post_image;
        unlink($img);

        BlogPost::find($id)->delete();

            $notification = array(
                'message' => 'Blog Post Berhasil Dihapus',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);

    }// End Method     

public function blogDetails($slug)
{
    $blog = BlogPost::where('post_slug', $slug)->firstOrFail();

    $bcategory = BlogCategory::latest()->get();
    $post = BlogPost::latest()->limit(3)->get();

    return view('frontend.blog.blog_details', compact('blog', 'bcategory', 'post'));
}


    public function blogCatList($id){

        $blog = BlogPost::where('blogcat_id',$id)->get();
        $breadcat = BlogCategory::where('id',$id)->first();
        $bcategory = BlogCategory::latest()->get();
        $post = BlogPost::latest()->limit(3)->get();
        return view('frontend.blog.blog_cat_list',compact('blog','breadcat','bcategory','post'));

    }// End Method 

    public function blogList(){

        $blog = BlogPost::latest()->paginate(2);
        $bcategory = BlogCategory::latest()->get();
        $post = BlogPost::latest()->limit(3)->get();
        return view('frontend.blog.blog_list',compact('blog','bcategory','post'));


    }// End Method 

    public function markBlogRead($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $blog = BlogPost::findOrFail($id);
        $user = Auth::user();
        $userId = $user->id;

        // Ambil list user yang sudah baca dari kolom JSON
        $readUsers = $blog->read_users
            ? json_decode($blog->read_users, true)
            : [];

        if (!is_array($readUsers)) {
            $readUsers = [];
        }

        // Jika user sudah pernah tercatat membaca artikel ini → jangan tambah poin lagi
        if (in_array($userId, $readUsers)) {
            // Mengirim total poin saat ini agar tampilan bisa disinkronkan
            return response()->json([
                'success' => true, 
                'message' => 'Already counted',
                'total_points' => $user->total_points
            ]);
        }

        // Cari rule poin "Membaca 1 Artikel"
        $rule = Point::where('activity', 'Membaca 1 Artikel')
            ->where('status', 'active')
            ->first();

        $pointsAdded = 0;

        if ($rule) {
            // ... (Catat ke user_points) ...
            UserPoint::create([
                'user_id'       => $userId,
                'point_rule_id' => $rule->id,
                'activity'      => $rule->activity,
                'points'        => $rule->points,
            ]);

            // Tambah ke total_points user
            $user->increment('total_points', $rule->points);

            $pointsAdded = $rule->points;
        }

        // Tandai user ini sudah membaca artikel ini
        $readUsers[] = $userId;
        $blog->read_users = json_encode($readUsers);
        $blog->save();

        // ⭐️ Perubahan Penting ⭐️
        // Ambil data user terbaru setelah increment (agar total_points sudah ter-update)
        $user->refresh(); 
        $latestTotalPoints = $user->total_points; 

        return response()->json([
            'success'       => true,
            'message'       => 'Points added',
            'points_added'  => $pointsAdded,
            // Ini yang akan digunakan oleh JavaScript untuk pembaruan
            'total_points'  => $latestTotalPoints, 
        ]);
    }
}
