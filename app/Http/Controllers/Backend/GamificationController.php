<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Point;
use App\Models\Badge;


class GamificationController extends Controller
{
    //
    public function pointsRules(){
        $points = Point::latest()->get();
        return view('admin.backend.gamification.all_point',compact('points'));

    }// End Method
    public function createPointsRule(){
        $points = Point::latest()->get();
        return view('admin.backend.gamification.create_point',compact('points'));
    }// End Method

    public function storePointsRule(Request $request){
        Point::insert([
            'activity' => $request->activity,
            'points' => $request->points,
            'description' => $request->description,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $notification = array(
            'message' => 'Aturan Poin Berhasil Ditambahkan',
            'alert-type' => 'success'
        );
        return redirect()->route('points.rules')->with($notification);
    }// End Method
}
