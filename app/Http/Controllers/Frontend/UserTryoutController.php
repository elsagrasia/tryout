<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TryoutPackage;
use App\Models\UserTryout; 
use App\Models\ResultTryout;
use App\Models\UserAnswer;
use App\Models\Question; 
use App\Models\TryoutHistory;
use App\Models\Category;
use Carbon\Carbon;

class UserTryoutController extends Controller
{

    public function myTryout()
    {
        $userId = Auth::id();

        // Ambil daftar tryout yang user ikuti
        $myTryouts = UserTryout::with('tryoutPackage')
            ->where('user_id', $userId)
            ->get();

        // Kirim ke view
        return view('frontend.mytryout.my_all_tryout', compact('myTryouts'));
    }

    // public function AddToTryout(Request $request, $tryoutPackage_id){
        
    //     if (Auth::check()) {
    //        $exists = UserTryout::where('user_id',Auth::id())->where('tryout_package_id',$tryoutPackage_id)->first();
        
    //         if (!$exists) {
    //             UserTryout::insert([
    //                 'user_id' => Auth::id(),
    //                 'tryout_package_id' => $tryoutPackage_id,
    //                 'created_at' => Carbon::now(),
    //             ]);
    //             return response()->json(['success' => 'Successfully joined this tryout!']);
    //         }else {
    //             return response()->json(['error' => 'You have already joined this tryout']);
    //         }
    
    //     }else{
    //         return response()->json(['error' => 'At First Login Your Account']);
    //     } 

    // }

    public function AddToTryout(Request $request, $tryoutPackage_id)
    {
        if (Auth::check()) {
            $exists = UserTryout::where('user_id', Auth::id())
                ->where('tryout_package_id', $tryoutPackage_id)
                ->first();

            if (!$exists) {
                UserTryout::create([
                    'user_id' => Auth::id(),
                    'tryout_package_id' => $tryoutPackage_id,
                    'created_at' => Carbon::now(),
                ]);
                $notification = array(
                    'message' => 'Berhasil bergabung ke tryout ini!',
                    'alert-type' => 'success'
                );
                return redirect()->route('my.tryout')->with($notification);
                
            }else {
                $notification = array(
                    'message' => 'Kamu sudah bergabung di tryout ini',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
        }else{
            $notification = array(
                'message' => 'Silakan login terlebih dahulu',
                'alert-type' => 'error'
            );
            return redirect()->route('login')->with($notification);
        }

    }



    public function StartTryout($id)
    {
        $tryout = TryoutPackage::with('questions')->findOrFail($id);
        return view('frontend.mytryout.start_tryout', compact('tryout'));
    }


    public function DeleteTryout($id)
    {
        $tryout = UserTryout::findOrFail($id);
        $tryout->delete();

        return redirect()->back()->with('success', 'Tryout berhasil dihapus.');
    }
public function SubmitTryout(Request $request, $id)
{
    $user_id = Auth::id();
    $tryout_package_id = $request->tryout_package_id ?? $id;
    $answers = $request->answers ?? [];

    // ✅ Ambil soal dari paket
    $package = TryoutPackage::with('questions')->findOrFail($tryout_package_id);
    $questions = $package->questions;

    $correct = 0;
    $wrong = 0;
    $unanswered = 0;

    // ========================
    // LOOP SETIAP SOAL
    // ========================
    foreach ($questions as $question) {
        $qid = $question->id;

        // ✅ kalau key tidak ada / null / kosong → tidak dijawab
        if (!array_key_exists($qid, $answers) || $answers[$qid] === null || $answers[$qid] === '') {
            $unanswered++;
            $selected_option = null;
            $is_correct = false;
        } else {
            $selected_option = strtoupper($answers[$qid]);
            $is_correct = strtoupper($selected_option) === strtoupper($question->correct_option);
            if ($is_correct) $correct++; else $wrong++;
        }

        // ✅ simpan jawaban ke user_answers
        UserAnswer::updateOrCreate(
            [
                'user_id' => $user_id,
                'tryout_package_id' => $tryout_package_id,
                'question_id' => $qid,
            ],
            [
                'selected_option' => $selected_option,
                'is_correct' => $is_correct,
            ]
        );
    }

    // ========================
    // HITUNG NILAI
    // ========================
    $total_questions = $questions->count();
    $score = $total_questions > 0 ? round(($correct / $total_questions) * 100, 2) : 0;
    $elapsed_time = $request->input('elapsed_time', 0);

    // ========================
    // SIMPAN KE RESULT_TRYOUTS
    // ========================
    $result = ResultTryout::firstOrNew([
        'user_id' => $user_id,
        'tryout_package_id' => $tryout_package_id,
    ]);

    $result->total_questions = $total_questions;
    $result->correct_answers = $correct;
    $result->wrong_answers   = $wrong;
    $result->unanswered      = $unanswered;
    $result->score           = $score;
    $result->elapsed_time    = $elapsed_time;
    $result->finished_at     = now();
    $result->save();

    // ✅ debug opsional
    // dd(compact('total_questions', 'correct', 'wrong', 'unanswered', 'score'));

    return redirect()->route('user.tryout.result', $tryout_package_id)
        ->with('success', 'Tryout selesai! Hasil Anda telah disimpan.');
}


    
public function ResultTryout($id)
{
    $user_id = Auth::id();

    // Ambil semua jawaban user + relasi soal
    $userAnswers = UserAnswer::with('question.packages')
        ->where('tryout_package_id', $id)
        ->where('user_id', $user_id)
        ->get();

    // Ambil info paket & nama tryout
    $package = TryoutPackage::with('questions')->findOrFail($id);
    $tryoutName = $package->tryout_name ?? '-';
    $totalQuestions = $package->questions->count();

    // Ambil data hasil terakhir user
    $result = ResultTryout::where('user_id', $user_id)
        ->where('tryout_package_id', $id)
        ->latest()
        ->first();

    // Ubah ke struktur hasil untuk blade
    $results = $userAnswers->map(function ($item) {
        return [
            'question_id'    => $item->question_id,
            'question'       => $item->question->question_text,
            'vignette'       => $item->question->vignette,
            'image'          => $item->question->image ?? null,
            'options'        => [
                'a' => $item->question->option_a,
                'b' => $item->question->option_b,
                'c' => $item->question->option_c,
                'd' => $item->question->option_d,
                'e' => $item->question->option_e,
            ],
            'user_answer'    => strtolower($item->selected_option),
            'correct_option' => strtolower($item->question->correct_option),
            'explanation'    => $item->question->explanation,
            'is_correct'     => $item->is_correct,
        ];
    });
    return view('frontend.mytryout.tryout_result', [
        'tryoutName'      => $tryoutName,
        'results'         => $results,
        'correctCount'    => $result->correct_answers ?? 0,
        'wrongCount'      => $result->wrong_answers ?? 0,
        'unansweredCount' => $result->unanswered ?? 0,
        'finalScore'      => $result->score ?? 0,
        'elapsed_time'    => $result->elapsed_time ?? 0,
        'totalQuestions'  => $totalQuestions,
        'id'              => $id,
        'allQuestionIds'  => $package->questions->pluck('id')->toArray(),
    ]);
}



 public function explanation($id)
{
    $user_id = Auth::id();

    // Ambil semua jawaban user + relasi soal
    $userAnswers = UserAnswer::with('question.packages')
        ->where('tryout_package_id', $id)
        ->where('user_id', $user_id)
        ->get();

    // Ambil info paket & nama tryout
    $package = TryoutPackage::with('questions')->findOrFail($id);
    $tryoutName = $package->tryout_name ?? '-';
    $category = $package->category_id ?? null;
    $totalQuestions = $package->questions->count();

    // Ambil data hasil terakhir user
    $result = ResultTryout::where('user_id', $user_id)
        ->where('tryout_package_id', $id)
        ->latest()
        ->first();

    // ✅ Urutkan jawaban berdasarkan urutan soal di paket
    $orderedIds = $package->questions->pluck('id')->toArray();
    $userAnswers = $userAnswers->sortBy(function ($answer) use ($orderedIds) {
        return array_search($answer->question_id, $orderedIds);
    })->values();

    $categories = \App\Models\Category::whereIn('id', 
        $package->questions->pluck('category_id')->unique()
    )->get();

    // ✅ Ubah ke struktur hasil untuk blade
    $results = $userAnswers->map(function ($item) {
        return [
            'question_id'    => $item->question_id,
            'category_id'    => $item->question->category_id ?? null,
            'question'       => $item->question->question_text,
            'vignette'       => $item->question->vignette,
            'image'          => $item->question->image ?? null,
            'options'        => [
                'a' => $item->question->option_a,
                'b' => $item->question->option_b,
                'c' => $item->question->option_c,
                'd' => $item->question->option_d,
                'e' => $item->question->option_e,
            ],
            'user_answer'    => strtolower($item->selected_option),
            'correct_option' => strtolower($item->question->correct_option),
            'explanation'    => $item->question->explanation,
            'is_correct'     => $item->is_correct,
        ];
    });

    // ✅ Kirim ke blade
    return view('frontend.mytryout.explanation', [
        'tryoutName'      => $tryoutName,
        'results'         => $results,
        'correctCount'    => $result->correct_answers ?? 0,
        'wrongCount'      => $result->wrong_answers ?? 0,
        'unansweredCount' => $result->unanswered ?? 0,
        'finalScore'      => $result->score ?? 0,
        'elapsed_time'    => $result->elapsed_time ?? 0,
        'totalQuestions'  => $totalQuestions,
        'id'              => $id,
        'allQuestionIds'  => $package->questions->pluck('id')->toArray(),
        'categories'      => $categories,
    ]);
}





}



