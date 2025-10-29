<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TryoutHistory;
use App\Models\ResultTryout;
use Illuminate\Http\Request;

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
}
