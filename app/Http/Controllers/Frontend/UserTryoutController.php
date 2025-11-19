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
use App\Models\Badge;
use App\Models\UserBadge;
use App\Models\Question; 
use App\Models\TryoutHistory;
use App\Models\Category;
use Carbon\Carbon;

class UserTryoutController extends Controller
{

    // public function myTryout()
    // {
    //     $userId = Auth::id();

    //     // Ambil daftar tryout yang user ikuti
    //     $myTryouts = UserTryout::with('tryoutPackage')
    //         ->where('user_id', $userId)
    //         ->get();

    //     // Kirim ke view
    //     return view('frontend.mytryout.my_all_tryout', compact('myTryouts'));
    // }
    public function myTryout()
    {
        $userId = Auth::id();

        $myTryouts = UserTryout::with('tryoutPackage')
            ->where('user_id', $userId)
            ->get();

        // Ambil hasil terakhir user untuk setiap tryout
        $results = \App\Models\ResultTryout::where('user_id', $userId)
            ->get()
            ->keyBy('tryout_package_id');

        // ---- Tambahan untuk tab "Contest" (group per bidang) ----
        // GANTI 'bidang_name' sesuai field di model TryoutPackage kamu
        $tryoutsByBidang = $myTryouts->groupBy(function ($item) {
            return $item->tryoutPackage->category_name ?? 'Lainnya';
            // contoh lain:
            // return $item->tryoutPackage->category->name ?? 'Lainnya';
        });
        // ----------------------------------------------------------

        return view(
            'frontend.mytryout.my_all_tryout',
            compact('myTryouts', 'results', 'tryoutsByBidang')
        );
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


    $questionOrder = session('question_order_' . $id);
    if (!$questionOrder) {
        $questionOrder = $tryout->questions->shuffle()->pluck('id')->toArray();
        session(['question_order_' . $id => $questionOrder]);
    }

    $tryout->questions = $tryout->questions->sortBy(function ($q) use ($questionOrder) {
        return array_search($q->id, $questionOrder);
    })->values();

    return view('frontend.mytryout.start_tryout', compact('tryout'));
}


   

    // GET progress + answers
public function getProgress($id)
{
    $userId = auth()->id();

    // Pastikan ada record dummy untuk timer
    $timer = UserAnswer::firstOrCreate([
        'user_id' => $userId,
        'tryout_package_id' => $id,
        'question_id' => 0
    ], [
        'selected_option' => null
    ]);

    // Ambil jawaban yang sudah ada
    $answers = UserAnswer::where('user_id', $userId)
        ->where('tryout_package_id', $id)
        ->where('question_id', '>', 0)
        ->pluck('selected_option','question_id');

    return response()->json([
        'elapsed_seconds' => $timer->elapsed_time ?? 0,
        'answers' => $answers
    ]);
}


// POST save-progress
public function saveProgress(Request $request, $id)
{
    $userId = auth()->id();

    // Simpan timer
    $timer = UserAnswer::firstOrCreate([
        'user_id' => $userId,
        'tryout_package_id' => $id,
        'question_id' => 0
    ]);
    $timer->elapsed_time = $request->input('elapsed_seconds', 0);
    $timer->save();

    // Simpan jawaban
    foreach($request->input('answers', []) as $qid => $val) {
        UserAnswer::updateOrCreate(
            ['user_id'=>$userId,'tryout_package_id'=>$id,'question_id'=>$qid],
            ['selected_option'=>$val]
        );
    }

    return response()->json(['status'=>'success']);
}


    public function DeleteTryout($id)
    {
        $tryout = UserTryout::findOrFail($id);
        $tryout->delete();

        return redirect()->back()->with('success', 'Tryout berhasil dihapus.');
    }

    public function confirm($id)
{
    $tryout = TryoutPackage::with('questions')->findOrFail($id);
    return view('frontend.mytryout.confirmation', compact('tryout'));
}

public function SubmitTryout(Request $request, $id)
{
    $user_id = Auth::id();
    $tryout_package_id = $request->tryout_package_id ?? $id;
    $answers = $request->answers ?? [];

    if (is_string($answers)) {
        $decoded = json_decode($answers, true);
        $answers = is_array($decoded) ? $decoded : [];
    }

    // 🔹 Tambahan: doubts
    $doubts = $request->doubts ?? [];
    if (is_string($doubts)) {
        $decodedDoubts = json_decode($doubts, true);
        $doubts = is_array($decodedDoubts) ? $decodedDoubts : [];
    }

    $package = TryoutPackage::with('questions')->findOrFail($tryout_package_id);
    $questions = $package->questions;
    
    $correct = 0; 
    $wrong = 0; 
    $unanswered = 0; 
    $doubtCount = 0;

    foreach ($questions as $q) {
        $qid = $q->id;

        // cek apakah soal ini ditandai ragu
        $is_doubt = !empty($doubts[$qid]); // true/false

        if (!array_key_exists($qid, $answers) || $answers[$qid] === null || $answers[$qid] === '') {
            $unanswered++;
            $selected_option = null;
            $is_correct = false;
        } else {
            $selected_option = strtoupper($answers[$qid]);
            $is_correct = $selected_option === strtoupper($q->correct_option);
            $is_correct ? $correct++ : $wrong++;
        }

        // Menyimpan jawaban ke database
        UserAnswer::updateOrCreate(
            ['user_id' => $user_id, 'tryout_package_id' => $tryout_package_id, 'question_id' => $qid],
            ['selected_option' => $selected_option, 'is_correct' => $is_correct, 'is_doubt' => $is_doubt]
        );

        // Jika soal ini ditandai ragu, tambah jumlah jawaban ragu
        if ($is_doubt) {
            $doubtCount++;
        }
    }

    $total_questions = $questions->count();
    $score = $total_questions > 0 ? round(($correct / $total_questions) * 100, 2) : 0;
    $elapsed_time = (int) $request->input('elapsed_time', 0);

    // Simpan/Update hasil
    $result = ResultTryout::firstOrNew([
        'user_id' => $user_id,
        'tryout_package_id' => $tryout_package_id,
    ]);
    $result->total_questions = $total_questions;
    $result->correct_answers = $correct;
    $result->wrong_answers = $wrong;
    $result->unanswered = $unanswered;
    $result->doubt_answers = $doubtCount;
    $result->score = $score;
    $result->elapsed_time = $elapsed_time;
    $result->finished_at = now();
    $result->save();

    // ====== POIN (opsional, contoh singkat) ======
    $rule = Point::where('activity', 'Menyelesaikan 1 Tryout')->where('status', 'active')->first();
    if ($rule) {
        UserPoint::create([
            'user_id' => $user_id, 'point_rule_id' => $rule->id, 'activity' => $rule->activity, 'points' => $rule->points,
        ]);
        Auth::user()->increment('total_points', $rule->points);
    }

    // Bonus per jawaban benar
    $perCorrect = config('points.per_correct', 5);
    $bonusPoints = $correct * $perCorrect;
    if ($bonusPoints > 0) {
        UserPoint::create([
            'user_id' => $user_id, 'point_rule_id' => null, 'activity' => 'Bonus Jawaban Benar', 'points' => $bonusPoints,
        ]);
        Auth::user()->increment('total_points', $bonusPoints);
    }

    // --- Cek Konsisten Unggul (Pemberian Badge) ---
    $completedTryouts = ResultTryout::where('user_id', $user_id)->count();
    $badges = Badge::where('type', 'tryout')->where('status', 'active')->orderBy('threshold')->get();
    $newlyEarned = [];

    // Pengecekan untuk badge Konsisten Unggul jika sudah menyelesaikan 5 tryout
    if ($completedTryouts >= 5) {
        // Hitung rata-rata nilai
        $averageScore = ResultTryout::where('user_id', $user_id)->avg('score');

        // Cek jika rata-rata >= 85 untuk badge "Konsisten Unggul"
        if ($averageScore >= 85) {
            $badge = Badge::where('name', 'Konsisten Unggul')->first();
            if ($badge) {
                $newlyEarned[] = [
                    'id' => $badge->id,
                    'name' => $badge->name,
                    'description' => $badge->description ?? 'Konsisten dalam tryout dengan rata-rata nilai lebih dari 85.',
                    'icon' => $badge->icon
                ];
                UserBadge::create([
                    'user_id' => $user_id,
                    'badge_id' => $badge->id,
                    'earned_at' => now(),
                ]);
            }
        }
    }

    // Pemberian badge berdasarkan threshold lainnya (seperti Bronze, Silver, etc.)
    foreach ($badges as $badge) {
        $already = UserBadge::where('user_id', $user_id)
            ->where('badge_id', $badge->id)
            ->exists();

        if (!$already && $completedTryouts >= (int) $badge->threshold) {
            UserBadge::create([
                'user_id' => $user_id,
                'badge_id' => $badge->id,
                'earned_at' => now(),
            ]);

            // ICON SELALU LOKAL → LANGSUNG ASSET()
            $newlyEarned[] = [
                'id' => $badge->id,
                'name' => $badge->name,
                'description' => $badge->description ?? '',
                'icon' => $badge->icon,
            ];
        }
    }

    // reset sesi urutan soal
    session()->forget('question_order_' . $tryout_package_id);

    // Kirim data badge baru ke halaman hasil
    return redirect()
        ->route('user.tryout.result', $tryout_package_id)
        ->with([
            'message' => 'Tryout selesai! Nilai & poin berhasil ditambahkan.',
            'alert-type' => 'success',
            'earned_badges' => $newlyEarned,
        ]);
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
                'is_doubt'       => (bool) $item->is_doubt,
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

        // $histories = ResultTryout::with('tryoutPackage')
        //     ->where('user_id', Auth::id())
        //     ->latest()
        //     ->get();
        $histories = \App\Models\ResultTryout::with('tryoutPackage')
            ->where('user_id', Auth::id())
            ->select('tryout_package_id', \DB::raw('MAX(id) as latest_id'))
            ->groupBy('tryout_package_id')
            ->pluck('latest_id');

        $histories = \App\Models\ResultTryout::with('tryoutPackage')
            ->whereIn('id', $histories)
            ->orderByDesc('updated_at')
            ->get();

        return view('frontend.mytryout.tryout_result', [
            'tryoutName'      => $tryoutName,
            'results'         => $results,
            'result'            => $result,
            'correctCount'    => $result->correct_answers ?? 0,
            'wrongCount'      => $result->wrong_answers ?? 0,
            'unansweredCount' => $result->unanswered ?? 0,
            'doubtCount'      => $result->doubt_answers ?? 0,
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
                'is_doubt'       => (bool) $item->is_doubt,
                
            ];
        });

        // ✅ Kirim ke blade
        return view('frontend.history.explanation', [
            'tryoutName'      => $tryoutName,
            'results'         => $results,
            'correctCount'    => $result->correct_answers ?? 0,
            'wrongCount'      => $result->wrong_answers ?? 0,
            'unansweredCount' => $result->unanswered ?? 0,
            'doubtCount'      => $result->doubt_answers ?? 0,
            'finalScore'      => $result->score ?? 0,
            'elapsed_time'    => $result->elapsed_time ?? 0,
            'totalQuestions'  => $totalQuestions,
            'id'              => $id,
            'allQuestionIds'  => $package->questions->pluck('id')->toArray(),
            'categories'      => $categories,
        ]);
    }

}



