<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TryoutPackage;
use App\Models\Category;
use App\Models\Question;
use App\Models\Answer;
use App\Models\ResultTryout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;  

class TryoutPackageController extends Controller
{

    public function allTryoutPackages() {
        $id = Auth::user()->id;
        $tryoutPackages = TryoutPackage::where('instructor_id',$id)->orderBy('id','desc')->get();
        return view('instructor.tryout_packages.all_tryout', compact('tryoutPackages'));
    }

    public function addTryoutPackage() {
        $id = Auth::user()->id;
        $categories = Category::all();
        // return view('instructor.tryout_packages.add_tryout');
        return view('instructor.tryout_packages.add_tryout', compact('categories'));
    }

    // public function storeTryoutPackage(Request $request) {
    //     $request->validate([
    //         'tryout_name' => 'required',
    //         // 'category_id' => 'required',
    //     ]);

    //     $id = Auth::user()->id;
    //     TryoutPackage::insert([
    //         'tryout_name' => $request->tryout_name,
    //         'description' => $request->description,
    //         'instructor_id' => $id,
    //         'duration' => $request->duration,
    //         'category_id' => $request->category_id, //buat ambil id category
    //         'total_questions' => 0,
    //         'status' => 'draft',
    //         'created_at' => Carbon::now(),
    //     ]);

    //     $notification = array(
    //         'message' => 'Tryout Package Inserted Successfully',
    //         'alert-type' => 'success'
    //     );
    //     return redirect()->route('all.tryout.packages')->with($notification);
    // }

    public function storeTryoutPackage(Request $request)
    {
        $request->validate([
            'tryout_name' => 'required',
        ]);

        // Cari category berdasarkan nama tryout
        $category = Category::whereRaw(
            'LOWER(category_name) LIKE ?', 
            ['%' . strtolower($request->tryout_name) . '%']
        )->first();

        $categoryId = $category ? $category->id : null;

        TryoutPackage::insert([
            'tryout_name' => $request->tryout_name,
            'description' => $request->description,
            'instructor_id' => Auth::user()->id,
            'category_id' => $categoryId,
            'duration' => $request->duration,          
            'total_questions' => 0,
            'status' => 'draft',
            'created_at' => Carbon::now(),
        ]);

        $notification = [
            'message' => 'Paket Tryout berhasil ditambahkan',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.tryout.packages')->with($notification);
    }

    public function editTryoutPackage($id) {
        $tryoutPackage = TryoutPackage::findOrFail($id);
        return view('instructor.tryout_packages.edit_tryout', compact('tryoutPackage'));
    }

    public function updateTryoutPackage(Request $request)
    {
        // validasi input
        $request->validate([
            'tryout_name'     => 'required|string|max:255',
            'duration'        => 'required|integer|min:1',
            'total_questions' => 'nullable|integer|min:0',
            'description'     => 'nullable|string',
        ]);

        // ambil record berdasarkan id dari hidden input
        $package = \App\Models\TryoutPackage::findOrFail($request->tryout_package_id);

        // update field
        $package->update([
            'tryout_name'     => $request->tryout_name,
            'duration'        => $request->duration,
            'total_questions' => $request->total_questions,
            'description'     => $request->description,
            'updated_at'      => Carbon::now(),
        ]);

        $notification = array(
                'message' => 'Paket Tryout berhasil diperbarui',
                'alert-type' => 'success'
            );
        return redirect()->route('all.tryout.packages')->with($notification);
    } // end method

    public function deleteTryoutPackage($id) {
        $tryoutPackage = TryoutPackage::findOrFail($id);
        // Hapus paket tryout
        $tryoutPackage->delete();

        $notification = array(
            'message' => 'Paket Tryout berhasil dihapus',
            'alert-type' => 'success'
        );
        return redirect()->route('all.tryout.packages')->with($notification);
    } // end method

    public function updateTryoutPackageStatus(Request $request)
    {
        $data = $request->validate([
            'tryout_package_id' => 'required|exists:tryout_packages,id',
            'status' => 'required|in:draft,published',
        ]);

        $tryout = TryoutPackage::findOrFail($data['tryout_package_id']);
        $tryout->status = $data['status']; // 'draft' / 'published'
        $tryout->save();

        return response()->json(['message' => 'Paket Tryout berhasil diperbarui']);
    }

    public function managePackage(TryoutPackage $package)
    {
        // baris untuk tabel: semua soal yang SUDAH ter-attach ke paket (lintas kategori)
        $rows = $package->questions()
            ->with('category')                          // Question::category()
            ->latest('package_question.created_at')
            ->get();

        // dropdown kategori
        $categories = Category::orderBy('category_name')->get();

        // bank soal per kategori (hanya yang BELUM ter-attach ke paket ini)
        $bankByCategory = Question::whereIn('category_id', $categories->pluck('id'))
            ->whereDoesntHave('packages', fn($q) => $q->where('tryout_package_id', $package->id))
            ->get()
            ->groupBy('category_id');

        return view('instructor.tryout_packages.category.add_tryout_category', compact(
            'package', 'rows', 'categories', 'bankByCategory'
        ));
    }

    // attach dari modal: pilih kategori + ceklis soal
    public function attachQuestions(Request $r, TryoutPackage $package)
    {
        $data = $r->validate([
            'category_id'   => 'required|exists:categories,id',
            'question_ids'  => 'required|array',
            'question_ids.*'=> 'integer',
        ]);

        // amankan: hanya soal dari kategori yang dipilih
        $ids = Question::whereIn('id', $data['question_ids'])
            ->where('category_id', $data['category_id'])
            ->pluck('id')
            ->all();

    
        if ($ids) {
            $package->questions()->syncWithoutDetaching($ids);

            // hitung ulang total soal
            $package->update([
                'total_questions' => $package->questions()->count(),
            ]);
        }

        return back()->with('message', 'Soal berhasil ditambahkan ke paket');
    }

    // detach 1 soal dari paket (soal tetap ada di bank)
    public function detachQuestion(TryoutPackage $package, Question $question)
    {
        $package->questions()->detach($question->id);
        return back()->with('message', 'Soal dihapus');
    }

    public function allPackageResult()
    {
        $id = Auth::user()->id;
        $tryoutPackages = TryoutPackage::where('instructor_id',$id)->orderBy('id','desc')->get();    
        
        $tryoutPackages = $tryoutPackages->map(function ($package) {
        // Menghitung jumlah peserta yang unik (user_id)
        $participantsCount = ResultTryout::where('tryout_package_id', $package->id)           
            ->distinct('user_id') // Menghitung user_id yang berbeda
            ->count('user_id');
        
        // Menghitung rata-rata nilai
        $averageScore = ResultTryout::where('tryout_package_id', $package->id)
            ->avg('score');  // Asumsi kolom 'score' berisi nilai tiap peserta

        // Menambahkan nilai-nilai yang dihitung ke dalam package
        $package->participants_count = $participantsCount;
        $package->average_score = round($averageScore, 2);  // Membulatkan rata-rata nilai ke 2 angka desimal

        return $package;
        });
        return view('instructor.result.all_result', compact('tryoutPackages'));
    }
    
    public function viewPackageResult($package_id)
    {
        $results = ResultTryout::where('tryout_package_id', $package_id)
            ->with('user') // eager load user relationship
            ->orderBy('score', 'desc')
            ->get();

        return view('instructor.result.package_result', compact('results'));
    }

    public function userResultTryout($tryout_id, $user_id)
    {
        $result = ResultTryout::where('tryout_package_id', $tryout_id)
            ->where('user_id', $user_id)
            ->with('tryoutPackage')
            ->firstOrFail();

        // ambil jawaban user langsung dari tabel user_answers
        $answers = \App\Models\UserAnswer::where('user_id', $user_id)
            ->where('tryout_package_id', $tryout_id)
            ->with('question')
            ->get();

        return view('instructor.result.user_result', compact('result', 'answers'));
    }

} // end class


