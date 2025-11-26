<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionImport;
use Carbon\Carbon;


class QuestionController extends Controller
{
    //
    public function allQuestion(){
        $id = Auth::user()->id;
        $category = Category::latest()->get();
        $question = Question::latest()->get();

        return view('instructor.question.all_question',compact('category','question',));
    } //end method

    public function addQuestion()
    {
        $categories = Category::latest()->get();
        return view('instructor.question.add_question', compact('categories'));
    } //end method

    public function storeQuestion(Request $request)
    {
        $id = Auth::user()->id;

         // validasi input
        $request->validate([

            'question_text' => 'required',
            'category_id' => 'required|exists:categories,id',
            'disease' => 'nullable|string',
            'vignette' => 'required|string',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'option_e' => 'required',
            'correct_option' => 'required|in:A,B,C,D,E',
            'explanation' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $imagePath = 'upload/question/' . $imageName;
            Image::make($image)->resize(300, 300)->save($imagePath);
        }

        Question::insert([
            'question_text' => $request->question_text,
            'category_id' => $request->category_id,
            'disease' => $request->disease,
            'vignette' => $request->vignette,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'option_e' => $request->option_e,
            'correct_option' => $request->correct_option,
            'explanation' => $request->explanation,
            'image' => $imagePath,
            'created_at' => Carbon::now(),
        ]);
       
        $notification = array(
            'message' => 'Pertanyaan Berhasil Ditambahkan',
            'alert-type' => 'success'
        );

        return redirect()->route('all.question')->with($notification);
    } //end method

    public function editQuestion($id) {
        $question = Question::findOrFail($id);
        $categories = Category::latest()->get();
        return view('instructor.question.edit_question', compact('question', 'categories'));
    }

    public function updateQuestion(Request $request) {
        $id = $request->id;

        // validasi input
        $request->validate([
            'question_text' => 'required',
            'category_id' => 'required|exists:categories,id',
            'disease' => 'nullable|string',
            'vignette' => 'required|string',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'option_e' => 'required',
            'correct_option' => 'required|in:A,B,C,D,E',
            'explanation' => 'nullable|string',
        ]);

        $question = Question::findOrFail($id);

        $imagePath = $question->image; // default to existing image path
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($question->image && file_exists($question->image)) {
                unlink($question->image);
            }
            $image = $request->file('image');
            $imageName = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $imagePath = 'upload/question/' . $imageName;
            Image::make($image)->resize(300, 300)->save($imagePath);
        }

        $question->update([
            'question_text' => $request->question_text,
            'category_id' => $request->category_id,
            'disease' => $request->disease,
            'vignette' => $request->vignette,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'option_e' => $request->option_e,
            'correct_option' => $request->correct_option,
            'explanation' => $request->explanation,
            'image' => $imagePath,
        ]);

        $notification = array(
            'message' => 'Pertanyaan Berhasil Diperbarui',
            'alert-type' => 'success'
        );

        return redirect()->route('all.question')->with($notification);
    } //end method

    public function deleteQuestion($id) {
        $question = Question::findOrFail($id);

        // Delete image if exists
        if ($question->image && file_exists($question->image)) {
            unlink($question->image);
        }

        $question->delete();

        $notification = array(
            'message' => 'Pertanyaan Berhasil Dihapus',
            'alert-type' => 'success'
        );

        return redirect()->route('all.question')->with($notification);
    } //end method

    public function importQuestion(){

        return view('instructor.question.import_question');

    }// End Method     
    public function import(Request $request){

        Excel::import(new QuestionImport, $request->file('import_file'));

        $notification = array(
            'message' => 'Pertanyaan Berhasil Diimpor',
            'alert-type' => 'success'
        );
        return redirect()->route('all.question')->with($notification);

    }// End Method
}


