<?php

namespace App\Http\Controllers;

use App\Models\Levels;
use Illuminate\Http\Request;
use Monolog\Level;

class LevelsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $levels = Levels::all()->sortBy('index');
        return view('level.index', compact('levels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Levels $levels)
    {
        // $levels = Levels::all();
        return view('level.create', compact('levels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'title' => 'required',
            'index' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if(!empty(request()->image)){
            $image_name = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/levels/'), $image_name);
        }
        $level = new Levels;
        $level->title = $request->title;
        $level->index = $request->index;
        if(!empty(request()->image)){
            $level->image = $image_name;
        }
        $level->save();
        return redirect()->back()->with('success', 'Level stored');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Levels  $levels
     * @return \Illuminate\Http\Response
     */
    public function show(Levels $levels)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Levels  $level
     * @return \Illuminate\Http\Response
     */
    public function edit(Levels $level)
    {
        return view('level.edit', compact('level'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Levels  $level
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Levels $level)
    {
        $this->validate(request(), [
            'title' => 'required',
            'index' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if(!empty(request()->image)){
            $image_name = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/levels/'), $image_name);
        }
        $level->title = $request->title;
        $level->index = $request->index;
        if(!empty(request()->image)){
            $level->image = $image_name;
        }
        $level->save();
        return redirect()->back()->with('success', 'Level updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Levels  $level
     * @return \Illuminate\Http\Response
     */
    public function destroy(Levels $level)
    {
        $level->delete();
        return back()->with('success', 'Level Deleted');
    }

    public function save_order(Request $request){
        $oders = $request->orders;
        foreach ($oders as $order){
            $orderObj = Levels::find($order['id']);
            $orderObj->index = $order['index'];
            $orderObj->save();
        }
        return response()->json('success', 200);
    }
}
