<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TryoutPackage;
use App\Models\UserTryout; 
use App\Models\ResultTryout;
use App\Models\UserAnswer;
use App\Models\UserPoint;
use App\Models\Point;
use App\Models\User;
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
        $tryout = TryoutPackage::with(['questions.category'])->findOrFail($id);

        // kalau session urutan belum ada → buat acak baru
        $questionOrder = session('question_order_' . $id);
        if (!$questionOrder) {
            $questionOrder = $tryout->questions->shuffle()->pluck('id')->toArray();
            session(['question_order_' . $id => $questionOrder]);
        }

        // urutkan pertanyaan sesuai urutan di session
        $tryout->questions = $tryout->questions->sortBy(function ($q) use ($questionOrder) {
            return array_search($q->id, $questionOrder);
        })->values();

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

    // ========================
    // TAMBAH POIN USER
    // ========================
    $rule = Point::where('activity', 'Menyelesaikan 1 Tryout')->where('status', 'active')->first();
    if ($rule) {
        UserPoint::create([
            'user_id' => $user_id,
            'point_rule_id' => $rule->id,
            'activity' => $rule->activity,
            'points' => $rule->points,
        ]);
        Auth::user()->increment('total_points', $rule->points);
    }

// ========================
// CEK & BERIKAN BADGE
// ========================
$completedTryouts = \App\Models\ResultTryout::where('user_id', $user_id)->count();

$badges = \App\Models\Badge::where('type', 'tryout')
    ->where('status', 'active')
    ->orderBy('threshold', 'asc')
    ->get();

foreach ($badges as $badge) {
    $alreadyHas = \App\Models\UserBadge::where('user_id', $user_id)
        ->where('badge_id', $badge->id)
        ->exists();

    if (!$alreadyHas && $completedTryouts >= $badge->threshold) {
        \App\Models\UserBadge::create([
            'user_id' => $user_id,
            'badge_id' => $badge->id,
            'earned_at' => now(),
        ]);

        session()->flash('success', 'Selamat! Kamu mendapatkan badge "' . $badge->name . '"');
    }
}

// ✅ HAPUS URUTAN SOAL AGAR RESET UNTUK TRYOUT BERIKUTNYA
session()->forget('question_order_' . $tryout_package_id);

// ========================
// NOTIFIKASI & REDIRECT
// ========================
$notification = array(
    'message' => 'Tryout selesai! Nilai & poin berhasil ditambahkan.',
    'alert-type' => 'success'
);
return redirect()->route('user.tryout.result', $tryout_package_id)->with($notification);

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

    // Map hasil jawaban untuk Blade
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

    // 🔹 Leaderboard per tryout: ambil semua peserta tryout ini
    $leaderboard = ResultTryout::with('user')
        ->where('tryout_package_id', $id)
        ->orderByDesc('score')
        ->take(50)
        ->get()
        ->map(function ($result, $index) {
            return [
                'rank' => $index + 1,
                'user_id' => $result->user->id,
                'name' => $result->user->name,
                'photo' => $result->user->photo,
                'score' => $result->score,
            ];
        });

    // 🔹 User & ranking saat ini dalam leaderboard tryout tersebut
    $currentUser = Auth::user();
    $currentRank = ResultTryout::where('tryout_package_id', $id)
        ->where('score', '>', $result->score ?? 0)
        ->count() + 1;

    $histories = ResultTryout::with('tryoutPackage')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

    return view('frontend.mytryout.tryout_result', [
        'tryoutName'      => $tryoutName,
        'results'         => $results,
        'result'            => $result,
        'correctCount'    => $result->correct_answers ?? 0,
        'wrongCount'      => $result->wrong_answers ?? 0,
        'unansweredCount' => $result->unanswered ?? 0,
        'finalScore'      => $result->score ?? 0,
        'elapsed_time'    => $result->elapsed_time ?? 0,
        'totalQuestions'  => $totalQuestions,
        'id'              => $id,
        'allQuestionIds'  => $package->questions->pluck('id')->toArray(),
        'leaderboard'     => $leaderboard,
        'currentUser'     => $currentUser,
        'currentRank'     => $currentRank,
        'histories'       => $histories
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
    $orderedIds = session('question_order_' . $id, $package->questions->pluck('id')->toArray());

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



