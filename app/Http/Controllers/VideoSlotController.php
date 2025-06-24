<?php

namespace App\Http\Controllers;

use App\Models\VideoSlot;
use Illuminate\Http\Request;
use App\Models\SlotsCategory;
use Intervention\Image\ImageManagerStatic as Image;

class VideoSlotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(VideoSlot $video_slots)
    {
        return view('video_slots.index', compact('video_slots'));
    }

    public function create($id = 0)
    {
        $slots_categories = SlotsCategory::all();
        if($id > 0){
            return view('video_slots.create', compact('id', 'slots_categories'));
        }
        return view('video_slots.create', compact('slots_categories'));
    }

    public function store(Request $request)
    {
        $this->validate(request(), [
            'featured_image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if (!empty(request()->featured_image)) {
            $original_img = $request->file('featured_image');
            $base_path = public_path('images/video/');
            $image_name = time().$original_img->getClientOriginalName();
            $image_path = $base_path.$image_name;
            Image::make($original_img->getRealPath())
                ->resize(400, 200)
                ->save($image_path);
        }

        $video_slot = new VideoSlot;
        if($video = Video::find($request->video_id)){
            $video_slot->video()->associate($video);
        }
        $video_slot->start_time = $request->start_time;
        $video_slot->end_time = $request->end_time;
        $video_slot->featured_sentence = $request->featured_sentence;
        if($request->slots_category != null ){
            $video_slot->slots_category()->associate(SlotsCategory::find($request->slots_category));
        }
        if (!empty(request()->featured_image)) {
            $video_slot->featured_image = $image_name;
        }
        $video_slot->save();

        return redirect()->back()->with('success', 'Video Slot Was Added');
    }

    public function edit($id)
    {
        $video_slot = VideoSlot::find($id);
        $slots_categories = SlotsCategory::all();
        return view('video_slots.edit', compact('video_slot', 'slots_categories'));
    }
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'featured_image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if (!empty(request()->featured_image)) {
            $original_img = $request->file('featured_image');
            $base_path = public_path('images/video/');
            $image_name = time().$original_img->getClientOriginalName();
            $image_path = $base_path.$image_name;
            Image::make($original_img->getRealPath())
                    ->resize(400, 200)
                    ->save($image_path);
        }

        $video_slot = VideoSlot::find($id);
        if($video = Video::find($request->video_id)){
            $video_slot->video()->associate($video);
        }
        $video_slot->start_time = $request->start_time;
        $video_slot->end_time = $request->end_time;
        $video_slot->featured_sentence = $request->featured_sentence;
        if($request->slots_category != null ){
            $video_slot->slots_category()->associate(SlotsCategory::find($request->slots_category));
        }

        if (!empty(request()->featured_image)) {
            $video_slot->featured_image = $image_name;
        }
        $video_slot->save();
        return redirect()->back()->with('success', 'Video Slot Was Updated');
    }
    public function destroy($id)
    {
        $video_slot = VideoSlot::find($id);
        $video_slot->delete();
        return redirect()->back()->with('success', 'Video Slot Deleted');
    }
}
