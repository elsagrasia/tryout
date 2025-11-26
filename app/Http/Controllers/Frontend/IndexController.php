<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tryout;
use App\Models\UserBadge;
use App\Models\Badge;
use App\Models\userTryout;
use App\Models\category;
use App\Models\resultTryout;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;
use App\Models\TryoutPackage;
use App\Models\Question;

class IndexController extends Controller
{
    


    public function UserDashboard()
    {
        $user = Auth::user();

        // Total data umum
        $totalTryout = UserTryout::where('user_id', $user->id)->count();
        $totalSelesai = ResultTryout::where('user_id', $user->id)->count();
        $rataSkor = ResultTryout::where('user_id', $user->id)->avg('score') ?? 0;
        $rataSkor = round($rataSkor, 2);

        // Ambil kategori unik dari tabel questions
        $categoryIds = Question::select('category_id')->distinct()->pluck('category_id');

        $categoryScores = [];

        foreach ($categoryIds as $categoryId) {
            // Ambil nama kategori dari tabel categories
            $categoryName = Category::where('id', $categoryId)->value('category_name') ?? 'Tanpa Kategori';

            // Hitung rata-rata skor untuk kategori ini
            $avgScore = ResultTryout::where('user_id', $user->id)
                ->whereHas('tryoutPackage.questions', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                })
                ->avg('score') ?? 0;

            $categoryScores[$categoryName] = round($avgScore, 2);
        }

        // Data untuk ChartJS
        $chartLabels = array_keys($categoryScores); // tampilkan category_name
        $chartData = array_values($categoryScores);

        return view('frontend.dashboard.index', compact(
            'totalTryout',
            'totalSelesai',
            'rataSkor',
            'chartLabels',
            'chartData'
        ));
    }
    
    public function myBadges()
    {
        $userId = Auth::id();

        $badges = \App\Models\Badge::orderBy('threshold', 'asc')->get();

        $ownedBadges = \App\Models\UserBadge::where('user_id', $userId)
            ->pluck('badge_id')
            ->toArray();

        return view('frontend.badge.all_badge', compact('badges', 'ownedBadges'));
    }

    public function userLeaderboard()
    {
      
        $leaderboard = User::where('role', 'student')
            ->with('badges') // <= eager load
            ->orderByDesc('total_points')
            ->select('id', 'name', 'total_points', 'photo')
            ->take(50)
            ->get();

        // Tambahkan data tryout & nilai rata-rata
        $leaderboard->transform(function ($user, $index) {
            $user->total_tryouts = ResultTryout::where('user_id', $user->id)->count();
            $user->average_score = round(ResultTryout::where('user_id', $user->id)->avg('score') ?? 0, 2);
            $user->rank = $index + 1;
            return $user;
        });

        // Pisahkan top 3 dan sisanya
        $topThree = $leaderboard->take(3);
        $others = $leaderboard->slice(3);

        $currentUser = Auth::user();
        $currentRank = User::where('role', 'student')
            ->where('total_points', '>', $currentUser->total_points)
            ->count() + 1;

        return view('frontend.dashboard.user_leaderboard', compact('leaderboard', 'topThree', 'others', 'currentUser', 'currentRank'));
    }


}
