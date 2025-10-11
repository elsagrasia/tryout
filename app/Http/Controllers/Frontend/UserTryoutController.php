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

    public function AddToTryout(Request $request, $tryoutPackage_id){
        
        if (Auth::check()) {
           $exists = UserTryout::where('user_id',Auth::id())->where('tryout_package_id',$tryoutPackage_id)->first();
        
            if (!$exists) {
                UserTryout::insert([
                    'user_id' => Auth::id(),
                    'tryout_package_id' => $tryoutPackage_id,
                    'created_at' => Carbon::now(),
                ]);
                return response()->json(['success' => 'Successfully joined this tryout!']);
            }else {
                return response()->json(['error' => 'You have already joined this tryout']);
            }
    
        }else{
            return response()->json(['error' => 'At First Login Your Account']);
        } 

    }

    public function StartTryout($id)
    {
        $tryout = TryoutPackage::findOrFail($id);
        return view('frontend.mytryout.start_tryout', compact('tryout'));
    }

    public function DeleteTryout($id)
    {
        $tryout = UserTryout::findOrFail($id);
        $tryout->delete();

        return redirect()->back()->with('success', 'Tryout berhasil dihapus.');
    }

    // public function SubmitTryout(Request $request, $id)
    // {
    //     $user_id = Auth::id();
    //     $tryout_package_id = $request->tryout_package_id ?? $id;
    //     $answers = $request->answers ?? []; // kalau user belum jawab semua

    //     $correct = 0;
    //     $wrong = 0;
    //     $total = count($answers); // jumlah soal yang benar-benar dijawab user

    //     // loop jawaban user
    //     foreach ($answers as $question_id => $selected_option) {
    //         $question = \App\Models\Question::find($question_id);

    //         if ($question && strtoupper($selected_option) == strtoupper($question->correct_answer)) {
    //             $correct++;
    //         } else {
    //             $wrong++;
    //         }
    //     }

    //     // ✅ Jumlah total soal dalam tryout (berbeda tiap tryout)
    //     $total_questions = \App\Models\Question::where('tryout_id', $tryout_package_id)->count();

    //     // ✅ Hitung score berdasarkan proporsi jawaban benar
    //     // Misal nilai maksimum 100
    //     $score = $total_questions > 0 ? round(($correct / $total_questions) * 100) : 0;

    //     // Simpan ke tabel result_tryouts
    //     \App\Models\ResultTryout::create([
    //         'user_id' => $user_id,
    //         'tryout_package_id' => $tryout_package_id,
    //         'total_questions' => $total_questions,
    //         'correct_answers' => $correct,
    //         'wrong_answers' => $wrong,
    //         'score' => $score, // sudah disesuaikan dengan total pertanyaan
    //     ]);

    //     return redirect()->route('user.tryout.result', $tryout_package_id)
    //         ->with('success', 'Tryout selesai! Hasil Anda telah disimpan.');
    // }

    // public function ResultTryout($id)
    // {
    //     $user_id = Auth::id();

    //     // 🔹 Ambil hasil tryout user berdasarkan ID paket tryout
    //     $result = \App\Models\ResultTryout::where('user_id', $user_id)
    //                 ->where('tryout_package_id', $id)
    //                 ->first();

    //     if (!$result) {
    //         return redirect()->back()->with('error', 'Hasil tryout tidak ditemukan.');
    //     }

    //     // 🔹 Ambil data paket tryout-nya
    //     $tryout = \App\Models\TryoutPackage::find($id);

    //     // 🔹 Ambil rincian soal & jawaban (kalau mau ditampilkan per soal)
    //     $answers = session('tryout_answers', []);

    //     $results = [];
    //     foreach ($answers as $question_id => $selected_option) {
    //         $question = \App\Models\Question::find($question_id);
    //         if ($question) {
    //             $results[] = [
    //                 'question' => $question->question_text,
    //                 'image' => $question->image ?? null,
    //                 'user_answer' => $selected_option,
    //                 'correct_answer' => $question->correct_answer,
    //                 'is_correct' => strtoupper($selected_option) == strtoupper($question->correct_answer),
    //                 'explanation' => $question->explanation ?? '-',
    //             ];
    //         }
    //     }

    //     // 🔹 Kirim semua data ke view
    //     return view('frontend.mytryout.tryout_result', [
    //         'tryout' => $tryout,
    //         'results' => $results,
    //         'finalScore' => $result->score,
    //     ]);
    // }

    public function SubmitTryout(Request $request, $id)
    {
        $user_id = Auth::id();
        $tryout_package_id = $request->tryout_package_id ?? $id;
        $answers = $request->answers ?? [];

        if (empty($answers)) {
            return redirect()->back()->with('error', 'Anda belum menjawab pertanyaan apa pun.');
        }

        $correct = 0;
        $wrong = 0;

        foreach ($answers as $question_id => $selected_option) {
            $question = Question::find($question_id);
            $is_correct = false;

            // ✅ Pastikan kolom benar: correct_option
            if ($question && strtoupper($selected_option) == strtoupper($question->correct_option)) {
                $is_correct = true;
                $correct++;
            } else {
                $wrong++;
            }

            // ✅ Simpan jawaban user
            UserAnswer::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'tryout_package_id' => $tryout_package_id,
                    'question_id' => $question_id,
                ],
                [
                    'selected_option' => strtoupper($selected_option),
                    'is_correct' => $is_correct,
                ]
            );
        }

        // ✅ Hitung skor akhir
        $total_questions = Question::where('tryout_id', $tryout_package_id)->count();
        $score = $total_questions > 0 ? round(($correct / $total_questions) * 100, 2) : 0;

        // ✅ Simpan hasil tryout
        ResultTryout::updateOrCreate(
            [
                'user_id' => $user_id,
                'tryout_package_id' => $tryout_package_id,
            ],
            [
                'total_questions' => $total_questions,
                'correct_answers' => $correct,
                'wrong_answers' => $wrong,
                'score' => $score,
            ]
        );

        return redirect()->route('user.tryout.result', $tryout_package_id)
            ->with('success', 'Tryout selesai! Hasil Anda telah disimpan.');
    }

    
    // public function ResultTryout($id)
    // {
    // $userAnswers = UserAnswer::with('question.tryouts')
    //     ->where('tryout_package_id', $id)
    //     ->where('user_id', Auth::id())
    //     ->get();

    // // ✅ Ambil nama tryout sebelum data diubah jadi array
    // $tryoutName = optional($userAnswers->first()?->question?->tryouts?->first())->tryout_name ?? '-';

    // // ✅ Baru lakukan map setelahnya
    // $results = $userAnswers->map(function ($item) {
    //     return [
    //         'question_id' => $item->question_id,
    //         'question' => $item->question->question_text,
    //         'vignette' => $item->question->vignette,
    //         'options' => [
    //             'a' => $item->question->option_a,
    //             'b' => $item->question->option_b,
    //             'c' => $item->question->option_c,
    //             'd' => $item->question->option_d,
    //             'e' => $item->question->option_e,
    //         ],
    //         'user_answer' => strtolower($item->selected_option),
    //         'correct_option' => strtolower($item->question->correct_option),
    //         'explanation' => $item->question->explanation,
    //         'is_correct' => $item->is_correct,
    //     ];
    // });

    // // ✅ Hitung rekap hasil
    // $correctCount = $results->where('is_correct', true)->count();
    // $wrongCount = $results->where('is_correct', false)->count();
    // $totalQuestions = $results->count();
    // $unansweredCount = Question::where('tryout_id', $id)
    //     ->whereNotIn('id', $results->pluck('question_id'))
    //     ->count();

    // $finalScore = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;


    
    // return view('frontend.mytryout.tryout_result', compact(
    //     'tryoutName',
    //     'results',
    //     'correctCount',
    //     'wrongCount',
    //     'unansweredCount',
    //     'finalScore'
    // ));
    // }

    // public function ResultTryout($id)
    // {
    //     $userAnswers = UserAnswer::with('question.tryouts')
    //         ->where('tryout_package_id', $id)
    //         ->where('user_id', Auth::id())
    //         ->get();

    //     // ✅ Ambil nama tryout
    //     $tryoutName = optional($userAnswers->first()?->question?->tryouts?->first())->tryout_name ?? '-';

    //     // ✅ Ubah ke bentuk data hasil
    //     $results = $userAnswers->map(function ($item) {
    //         return [
    //             'question_id' => $item->question_id,
    //             'question' => $item->question->question_text,
    //             'vignette' => $item->question->vignette,
    //             'image' => $item->question->image ?? null,
    //             'options' => [
    //                 'a' => $item->question->option_a,
    //                 'b' => $item->question->option_b,
    //                 'c' => $item->question->option_c,
    //                 'd' => $item->question->option_d,
    //                 'e' => $item->question->option_e,
    //             ],
    //             'user_answer' => strtolower($item->selected_option),
    //             'correct_option' => strtolower($item->question->correct_option),
    //             'explanation' => $item->question->explanation,
    //             'is_correct' => $item->is_correct,
    //         ];
    //     });

    //     // ✅ Hitung rekap hasil
    //     // $correctCount = $results->where('is_correct', true)->count();
    //     // $wrongCount = $results->where('is_correct', false)->count();
    //     // $totalQuestions = $results->count();
    //     // $totalAllQuestions = Question::where('tryout_id', $id)->count();
    //     // $answeredCount = $results->count();
    //     // $unansweredCount = $totalAllQuestions - $answeredCount;
    //     // $finalScore = $totalAllQuestions > 0 ? round(($correctCount / $totalAllQuestions) * 100) : 0;

    //     $correctCount = $results->where('is_correct', true)->count();
    //     $wrongCount = $results->where('is_correct', false)->count();
    //     $totalQuestions = $results->count();
    //     $answeredCount = $results->count();
    //     $unansweredCount = Question::where('tryout_id', $id)
    //         ->whereNotIn('id', $results->pluck('question_id'))
    //         ->count();

    //     $finalScore = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;


    //     // ✅ Simpan ke tabel history
    //     TryoutHistory::updateOrCreate(
    //         [
    //             'user_id' => Auth::id(),
    //             'tryout_package_id' => $id,
    //         ],
    //         [
    //             'total_questions'   => $totalQuestions,
    //             'correct_count'     => $correctCount,
    //             'wrong_count'       => $wrongCount,
    //             'unanswered_count'  => $unansweredCount,
    //             'score'             => $finalScore,
    //             'finished_at'       => now(),
    //         ]
    //     );

    //     // ✅ Kirim semua variabel ke view
    //     return view('frontend.mytryout.tryout_result', compact(
    //         'tryoutName',
    //         'results',
    //         'answeredCount',
    //         'correctCount',
    //         'wrongCount',
    //         'unansweredCount',
    //         'finalScore',
    //         'totalQuestions'
    //     ));
    // }

    public function ResultTryout($id)
    {
        $userAnswers = UserAnswer::with('question.tryouts')
            ->where('tryout_package_id', $id)
            ->where('user_id', Auth::id())
            ->get();

        // ✅ Ambil nama tryout
        $tryoutName = optional($userAnswers->first()?->question?->tryouts?->first())->tryout_name ?? '-';

        // ✅ Ubah ke bentuk data hasil
        $results = $userAnswers->map(function ($item) {
            return [
                'question_id' => $item->question_id,
                'question' => $item->question->question_text,
                'vignette' => $item->question->vignette,
                'image' => $item->question->image ?? null,
                'options' => [
                    'a' => $item->question->option_a,
                    'b' => $item->question->option_b,
                    'c' => $item->question->option_c,
                    'd' => $item->question->option_d,
                    'e' => $item->question->option_e,
                ],
                'user_answer' => strtolower($item->selected_option),
                'correct_option' => strtolower($item->question->correct_option),
                'explanation' => $item->question->explanation,
                'is_correct' => $item->is_correct,
            ];
        });

        // ✅ Hitung hasil
        $correctCount = $results->where('is_correct', true)->count();
        $wrongCount = $results->where('is_correct', false)->count();
        $totalQuestions = $results->count();
        $answeredCount = $results->count();
        $unansweredCount = Question::where('tryout_id', $id)
            ->whereNotIn('id', $results->pluck('question_id'))
            ->count();

        // ✅ Skor akhir
        $finalScore = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        // ✅ Simpan ke tabel history
        TryoutHistory::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'tryout_package_id' => $id,
            ],
            [
                'total_questions'   => $totalQuestions,
                'correct_count'     => $correctCount,
                'wrong_count'       => $wrongCount,
                'unanswered_count'  => $unansweredCount,
                'score'             => $finalScore,
                'finished_at'       => now(),
            ]
        );

        // ✅ Kirim semua variabel ke view
        return view('frontend.mytryout.tryout_result', compact(
            'tryoutName',
            'results',
            'answeredCount',
            'correctCount',
            'wrongCount',
            'unansweredCount',
            'finalScore',
            'totalQuestions'
        ));
    }

    public function explanation($tryout_id)
    {
        $tryout = TryoutPackage::with('questions')->findOrFail($tryout_id);
        return view('frontend.mytryout.explanation', compact('tryout'));
    }




}


 

    // public function getTryoutList()
    // {
    //     $tryouts = Wishlist::with('tryout')
    //                 ->where('user_id', Auth::id())
    //                 ->latest()
    //                 ->get();

    //     $tryoutQty = Wishlist::where('user_id', Auth::id())->count();

    //     return response()->json(['tryouts' => $tryouts, 'tryoutQty' => $tryoutQty]);
    // } // End Method

    // public function removeTryout($id)
    // {
    //     Wishlist::where('user_id', Auth::id())
    //             ->where('id', $id)
    //             ->delete();

    //     return response()->json(['success' => 'Successfully removed from tryout']);
    // } 

