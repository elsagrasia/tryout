<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Category;
// use App\Models\SubCategory;
use App\Models\Course;
use App\Models\Course_goal;
use App\Models\CourseSection;
use App\Models\CourseLecture;
use App\Models\User;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;
use App\Models\TryoutPackage;

class IndexController extends Controller
{
    //
    // public function courseDetails($id, $slug)
    // {
    //     $course = Course::find($id);
    //     $goals = Course_goal::where('course_id',$id)->orderBy('id','DESC')->get();

    //     $ins_id = $course->instructor_id;
    //     $instructorCourses = Course::where('instructor_id',$ins_id)->orderBy('id','DESC')->get();

    //     $categories = Category::latest()->get();

    //     $cat_id = $course->category_id; 
    //     $relatedCourses = Course::where('category_id',$cat_id)->where('id','!=',$id)->orderBy('id','DESC')->limit(3)->get();

    //     return view('frontend.course.course_details',compact('course','goals','instructorCourses','categories','relatedCourses'));
    // } // End Method 
    
    //     public function categoryCourse($id, $slug){

    //     $courses = Course::where('category_id',$id)->where('status','1')->get();
    //     $category = Category::where('id',$id)->first();
    //     $categories = Category::latest()->get();
    //     return view('frontend.category.category_all',compact('courses','category','categories'));


    // }// End Method 

    // public function subcategoryCourse($id, $slug){

    //     $courses = Course::where('subcategory_id',$id)->where('status','1')->get();
    //     $subcategory = SubCategory::where('id',$id)->first();
    //     $categories = Category::latest()->get();
    //     return view('frontend.category.subcategory_all',compact('courses','subcategory','categories'));

    // } // End Method

    // public function instructorDetails($id)
    // {
    //     $instructor = User::find($id);
    //     $courses = Course::where('instructor_id', $id)->get();
    //     return view('frontend.instructor.instructor_details', compact('instructor', 'courses'));
    // } // End Method

    // public function tryoutDetails()
    // {
    //     $categories = Category::orderBy('category_name', 'ASC')->get();
    //     $tryoutPackages = TryoutPackage::with('user')->orderBy('id', 'DESC')->get();

    //     return view('frontend.index', compact('categories', 'tryoutPackages'));
    // }

    public function tryoutDetails($id)
    {
        // Ambil tryout utama berdasarkan ID
        $tryout = Tryout::findOrFail($id);

        // Ambil instructor dari tryout ini
        $instructor_id = $tryout->instructor_id;

        // Ambil daftar tryout lain dari instructor yang sama (kecuali yang sedang dibuka)
        $instructorTryouts = Tryout::where('instructor_id', $instructor_id)
            ->where('id', '!=', $id)
            ->orderBy('id', 'DESC')
            ->get();

        // Ambil semua kategori (kalau ingin ditampilkan di sidebar/tab)
        $categories = Category::latest()->get();

        // Ambil tryout lain dari kategori yang sama (related tryouts)
        $relatedTryouts = Tryout::where('category_id', $tryout->category_id)
            ->where('id', '!=', $id)
            ->orderBy('id', 'DESC')
            ->limit(3)
            ->get();

        return view('frontend.tryout.tryout_details', compact(
            'tryout',
            'instructorTryouts',
            'categories',
            'relatedTryouts'
        ));
    }

    public function instructorDetails($id)
    {
        $instructor = User::find($id);

        // tambahkan ini untuk ambil data tryout yang dibuat oleh instruktur
        $tryoutPackages = TryoutPackage::where('instructor_id', $id)->get();

        return view('frontend.instructor.instructor_details', compact('instructor', 'courses', 'tryoutPackages'));
    }




}
