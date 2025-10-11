<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use Intervention\Image\Facades\Image;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseLecture;
use App\Models\Course_goal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; //current time

// class CourseController extends Controller
// {
    
//     public function allCourse()
//     {
//         $id = Auth::user()->id;
//         $courses = Course::where('instructor_id',$id)->orderBy('id','desc')->get();
//         return view('instructor.courses.all_course',compact('courses'));

//     }

//     public function addCourse()
//     {
//         $categories = Category::latest()->get();
//         return view('instructor.courses.add_course', compact('categories'));
//     }

//     public function getSubCategory($category_id)
//     {
//         $subcat = SubCategory::where('category_id', $category_id)->orderBy('subcategory_name', 'ASC')->get();
//         return json_encode($subcat);
//     }

//     public function storeCourse(Request $request)
//     {
//         $request->validate([
//             'video' => 'required|mimes:mp4,mov,avi,flv,wmv|max:10000',
          
//         ]);
//         $image = $request->file('course_image');
//         $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
//         Image::make($image)->resize(370,246)->save('upload/course/thumbnail/'.$name_gen);
//         $save_url = 'upload/course/thumbnail/'.$name_gen;

//         $video = $request->file('video');
//         $videoName = time().'.'.$video->getClientOriginalExtension();
//         $video->move(public_path('upload/course/video/'), $videoName);
//         $save_video = 'upload/course/video/'.$videoName;

//         $course_id = Course::insertGetId([
//             'instructor_id' => Auth::user()->id,
//             'category_id' => $request->category_id,
//             'subcategory_id' => $request->subcategory_id,
//             'course_title' => $request->course_title,
//             'course_name' => $request->course_name,
//             'course_name_slug' => strtolower(str_replace(' ', '-', $request->course_name)),
//             'video' => $save_video,
//             'description' => $request->description,

//             'label' => $request->label,
//             'duration' => $request->duration,
//             'resources' => $request->resources,
//             'certificate' => $request->certificate,
//             'selling_price' => $request->selling_price,
//             'discount_price' => $request->discount_price,
//             'prerequisites' => $request->prerequisites,

//             'bestseller' => $request->bestseller,
//             'featured' => $request->featured,
//             'highestrated' => $request->highestrated,
//             'status' => 1,
//             'course_image' => $save_url,
//             'created_at' => Carbon::now(),
            
//         ]);

//         $goals = Count($request->course_goals);
//         if ($goals != null) {
//             for ($i = 0; $i < $goals; $i++) {
//                 $gcount = new Course_goal();
//                 $gcount->course_id = $course_id;
//                 $gcount->goal_name = $request->course_goals[$i];
//                 $gcount->save();
//             }
//         }
//         //course goals end
//         $notification = array(
//             'message' => 'Course Inserted Successfully',
//             'alert-type' => 'success'
//         );
//         return redirect()->route('all.course')->with($notification);
//     }

//     public function editCourse($id)
//     {
//         $course = Course::find($id);
//         $goals = Course_goal::where('course_id', $id)->get();
//         $categories = Category::latest()->get();
//         $subcategories = SubCategory::latest()->get();
//         return view('instructor.courses.edit_course', compact('course', 'categories', 'subcategories','goals'));
//     }

//       public function updateCourse(Request $request)
//     {
   
//         $cid = $request->course_id;
//             Course::find($cid)->update([
//             'instructor_id' => Auth::user()->id,
//             'category_id' => $request->category_id,
//             'subcategory_id' => $request->subcategory_id,
//             'course_title' => $request->course_title,
//             'course_name' => $request->course_name,
//             'course_name_slug' => strtolower(str_replace(' ', '-', $request->course_name)),
//             'description' => $request->description,

//             'label' => $request->label,
//             'duration' => $request->duration,
//             'resources' => $request->resources,
//             'certificate' => $request->certificate,
//             'selling_price' => $request->selling_price,
//             'discount_price' => $request->discount_price,
//             'prerequisites' => $request->prerequisites,

//             'bestseller' => $request->bestseller,
//             'featured' => $request->featured,
//             'highestrated' => $request->highestrated,
            
//         ]);

//         $notification = array(
//             'message' => 'Course Updated Successfully',
//             'alert-type' => 'success'
//         );
//         return redirect()->route('all.course')->with($notification);
//     }

//     public function updateCourseImage(Request $request)
//     {
//         $course_id = $request->id;
//         $old_image = $request->old_img;

//             $image = $request->file('course_image');
//             $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
//             Image::make($image)->resize(370,246)->save('upload/course/thumbnail/'.$name_gen);
//             $save_url = 'upload/course/thumbnail/'.$name_gen;

//         if (file_exists($old_image)) {
//             unlink($old_image);
//         }
//         Course::find($course_id)->update([
//             'course_image' => $save_url,
//             'updated_at' => Carbon::now(),
//         ]);
//         $notification = array(
//             'message' => 'Course Image Updated Successfully',
//             'alert-type' => 'success'
//         );
//         return redirect()->back()->with($notification);
//     }

//     public function updateCourseVideo(Request $request)
//     {
//         $course_id = $request->vid;
//         $old_video = $request->old_vid;

//         $video = $request->file('video');
//         $videoName = time().'.'.$video->getClientOriginalExtension();
//         $video->move(public_path('upload/course/video/'), $videoName);
//         $save_video = 'upload/course/video/'.$videoName;

//         if (file_exists($old_video)) {
//             unlink($old_video);
//         }
//         Course::find($course_id)->update([
//             'video' => $save_video,
//             'updated_at' => Carbon::now(),
//         ]);
//         $notification = array(
//             'message' => 'Course Video Updated Successfully',
//             'alert-type' => 'success'
//         );
//         return redirect()->back()->with($notification);
//     }


//     public function UpdateCourseGoal(Request $request){

//         $cid = $request->id;

//         if ($request->course_goals == NULL) {
//             return redirect()->back();
//         } else{

//             Course_goal::where('course_id',$cid)->delete();

//             $goles = Count($request->course_goals);
             
//                 for ($i=0; $i < $goles; $i++) { 
//                     $gcount = new Course_goal();
//                     $gcount->course_id = $cid;
//                     $gcount->goal_name = $request->course_goals[$i];
//                     $gcount->save();
//                 }  // end for
//         } // end else 

//         $notification = array(
//             'message' => 'Course Goals Updated Successfully',
//             'alert-type' => 'success'
//         );
//         return redirect()->back()->with($notification); 

//     }// End Method 
    
//     public function DeleteCourse($id){
//     $course = Course::find($id);
//     unlink($course->course_image);
//     unlink($course->video);

//     Course::find($id)->delete();

//     $goalsData = Course_goal::where('course_id',$id)->get();
//     foreach ($goalsData as $item) {
//         $item->goal_name;
//         Course_goal::where('course_id',$id)->delete();
//     }

//     $notification = array(
//         'message' => 'Course Deleted Successfully',
//         'alert-type' => 'success'
//     );
//     return redirect()->back()->with($notification); 

//     }// End Method 


//     public function addCourseLecture($id)
//     {
//         $course = Course::find($id);
//         $section = CourseSection::where('course_id',$id)->latest()->get();
//         return view('instructor.courses.section.add_course_lecture', compact('course','section'));
//     }

//     public function AddCourseSection(Request $request){

//         $cid = $request->id;

//         CourseSection::insert([
//             'course_id' => $cid,
//             'section_title' => $request->section_title, 
//         ]);

//         $notification = array(
//             'message' => 'Course Section Added Successfully',
//             'alert-type' => 'success'
//         );
//         return redirect()->back()->with($notification);  

//     }// End Method 

//     public function saveLecture(Request $request)
//     {
//         $lecture = new CourseLecture();
//         $lecture->course_id = $request->course_id;
//         $lecture->section_id = $request->section_id;
//         $lecture->lecture_title = $request->lecture_title;
//         $lecture->url = $request->lecture_url;
//         $lecture->content = $request->content;
//         $lecture->save();
//         return response()->json(['success' => 'Lecture Saved Successfully']);
//     }

//     public function editLecture($id)
//     {
//         $clecture = CourseLecture::find($id);
//         return view('instructor.courses.lecture.edit_course_lecture', compact('clecture'));
//     }

//     public function updateCourseLecture(Request $request)
//     {
//           $lid = $request->id;

//         CourseLecture::find($lid)->update([
//             'lecture_title' => $request->lecture_title,
//             'url' => $request->url,
//             'content' => $request->content,

//         ]);

//         $notification = array(
//             'message' => 'Course Lecture Updated Successfully',
//             'alert-type' => 'success'
//         );
//         return redirect()->back()->with($notification);   

//     }

//     public function DeleteLecture($id){

//         CourseLecture::find($id)->delete();

//         $notification = array(
//             'message' => 'Course Lecture Delete Successfully',
//             'alert-type' => 'success'
//         );
//         return redirect()->back()->with($notification);  

//     }// End Method 

//     public function DeleteSection($id){

//         $section = CourseSection::find($id);

//         /// Delete reated lectures 
//         $section->lectures()->delete();
//         // Delete the section 
//         $section->delete();

//         $notification = array(
//             'message' => 'Course Section Delete Successfully',
//             'alert-type' => 'success'
//         );
//         return redirect()->back()->with($notification); 

//     }// End Method 
// }
   