<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Point;
use App\Models\Badge;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
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

    public function editPointsRule($id){
        $point = Point::findOrFail($id);
        return view('admin.backend.gamification.edit_point',compact('point'));
    }// End Method

    public function updatePointsRule(Request $request){
        $point_id = $request->id;

        Point::findOrFail($point_id)->update([
            'activity' => $request->activity,
            'points' => $request->points,
            'description' => $request->description,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        $notification = array(
            'message' => 'Aturan Poin Berhasil Diupdate',
            'alert-type' => 'success'
        );
        return redirect()->route('points.rules')->with($notification);
    }// End Method

    public function deletePointsRule($id){
        Point::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Aturan Poin Berhasil Dihapus',
            'alert-type' => 'success'
        );
        return redirect()->route('points.rules')->with($notification);
    }// End Method

    public function badges(){
        $badges = Badge::latest()->get();
        return view('admin.backend.gamification.all_badge',compact('badges'));

    }// End Method

    public function createBadge(){
        return view('admin.backend.gamification.create_badge');
    }// End Method

    public function storeBadge(Request $request){
        $request->validate([
            'name' => 'required',
            'threshold' => 'required|integer',
            'type' => 'required',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        $iconPath = $request->file('icon');  
        $name_gen = hexdec(uniqid()).'.'.$iconPath->getClientOriginalExtension();
        Image::make($iconPath)->resize(370,247)->save('upload/badge/'.$name_gen);
        $save_url = 'upload/badge/'.$name_gen;

      
        Badge::insert([
            'name' => $request->name,
            'description' => $request->description,
            'threshold' => $request->threshold,
            'type' => $request->type,
            'icon' => $save_url,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $notification = array(
            'message' => 'Badge Berhasil Ditambahkan',
            'alert-type' => 'success'
        );
    return redirect()->route('badges')->with($notification);
    }// End Method

    public function toggleBadge($id)
    {
        $badge = Badge::findOrFail($id);

        // Ubah status otomatis
        $badge->status = $badge->status === 'active' ? 'inactive' : 'active';
        $badge->save();

        return redirect()->back()->with([
            'message' => 'Status badge ' . $badge->name . ' berhasil diperbarui!',
            'alert-type' => 'success'
        ]);
    }

    public function editBadge($id){
        $badge = Badge::findOrFail($id);
        return view('admin.backend.gamification.edit_badge',compact('badge'));
    }// End Method

    public function updateBadge(Request $request){
        $badge_id = $request->id;

        $request->validate([
            'name' => 'required',
            'threshold' => 'required|integer',
            'type' => 'required',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $badge = Badge::findOrFail($badge_id);

        if ($request->hasFile('icon')) {
            // Hapus ikon lama jika ada
            if ($badge->icon && file_exists(public_path($badge->icon))) {
                unlink(public_path($badge->icon));
            }

            $iconPath = $request->file('icon');  
            $name_gen = hexdec(uniqid()).'.'.$iconPath->getClientOriginalExtension();
            Image::make($iconPath)->resize(370,247)->save('upload/badge/'.$name_gen);
            $save_url = 'upload/badge/'.$name_gen;

            $badge->icon = $save_url;
        }

        $badge->name = $request->name;
        $badge->description = $request->description;
        $badge->threshold = $request->threshold;
        $badge->type = $request->type;
        $badge->status = $request->status;
        $badge->updated_at = now();
        $badge->save();

        $notification = array(
            'message' => 'Badge Berhasil Diupdate',
            'alert-type' => 'success'
        );
        return redirect()->route('badges')->with($notification);
    }// End Method
}
