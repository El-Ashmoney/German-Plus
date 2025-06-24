<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Video $videos)
    {
        return view('videos.index', compact('videos'));
    }

    public function create()
    {
        return view('videos.create');
    }

    public function store(Request $request)
    {
        $this->validate(request(), [
            'video_id' => 'required',
            'featured_image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'arabic_script' => 'mimes:txt|max:2048',
            'german_script' => 'mimes:txt|max:2048',
        ]);
        if (!empty(request()->featured_image)) {
            $image_name = time() . '.' . request()->featured_image->getClientOriginalExtension();
            request()->featured_image->move(public_path('images/videos'), $image_name);
        }
        if (!empty(request()->arabic_script)) {
            $ar_script_name = time() . '.' . request()->arabic_script->getClientOriginalExtension();
            request()->arabic_script->move(public_path('scripts/ar'), $ar_script_name);
        }
        if (!empty(request()->german_script)) {
            $de_script_name = time() . '.' . request()->german_script->getClientOriginalExtension();
            request()->german_script->move(public_path('scripts/de'), $de_script_name);
        }
        $video = new Video;
        $video->video_id = $request->video_id;

        if (!empty(request()->featured_image)) {
            $video->featured_image = $image_name;
        }
        if (!empty(request()->german_script)) {
            $video->german_script = $de_script_name;
        }

        if (!empty(request()->arabic_script)) {
            $video->arabic_script = $ar_script_name;
        }
        $video->save();

        return redirect()->back()->with('success', 'Video Was Added');
    }

    public function edit($id)
    {
        $video = Video::find($id);
        return view('videos.edit', compact('video'));
    }

    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'video_id' => 'required',
            'featured_image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'arabic_script' => 'mimes:txt|max:2048',
            'german_script' => 'mimes:txt|max:2048',
        ]);
        if (!empty(request()->featured_image)) {
            $image_name = time() . '.' . request()->featured_image->getClientOriginalExtension();
            request()->featured_image->move(public_path('images/videos'), $image_name);
        }
        if (!empty(request()->arabic_script)) {
            $ar_script_name = time() . '.' . request()->arabic_script->getClientOriginalExtension();
            request()->arabic_script->move(public_path('scripts/ar'), $ar_script_name);
        }
        if (!empty(request()->german_script)) {
            $de_script_name = time() . '.' . request()->german_script->getClientOriginalExtension();
            request()->german_script->move(public_path('scripts/de'), $de_script_name);
        }
        $video = Video::find($id);
        $video->video_id = $request->video_id;

        if (!empty(request()->featured_image)) {
            $video->featured_image = $image_name;
        }
        if (!empty(request()->german_script)) {
            $video->german_script = $de_script_name;
        }
        if (!empty(request()->arabic_script)) {
            $video->arabic_script = $ar_script_name;
        }
        $video->save();
        return redirect()->back()->with('success', 'Video Was Updated');
    }
    public function destroy($id)
    {
        $video = Video::find($id);
        $video->delete();
        return redirect()->back()->with('success', 'Video Deleted');
    }
}
