<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TryoutHistory;
use App\Models\ResultTryout;
use App\Models\UserAnswer;
use App\Models\TryoutPackage;
use App\Models\Question;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TryoutHistoryController extends Controller
{
    public function index()
    {
        $histories = ResultTryout::with('tryoutPackage')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('frontend.history.history_tryout', compact('histories'));
    }

    public function downloadPembahasan($id)
    {
        $userId = Auth::id();

        // $results = UserAnswer::with('question.category')
        //     ->where('tryout_package_id', $id)
        //     ->where('user_id', $userId)
        //     ->get();

         // Ambil semua jawaban user beserta question-nya
        $results = UserAnswer::with('question')
                    ->where('tryout_package_id', $id)
                    ->where('user_id', $userId)
                    ->get();

        // Ambil info tryout
        $tryout = TryoutPackage::findOrFail($id);

    

        // Generate PDF
        $pdf = Pdf::loadView('frontend.history.pembahasan', [
            'results' => $results,
            'tryout'  => $tryout,
            'tryout_package_id' => $id
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Pembahasan-'.$tryout->tryout_name.'.pdf');
    }



}
