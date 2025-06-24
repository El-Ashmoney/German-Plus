<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SlotsCategory;

class SlotsCategoryController extends Controller
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
    public function index(SlotsCategory $categories)
    {
        return view('slot_category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        return view('slot_category.create');
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
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if(!empty(request()->image)){
            $image_name = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/categories'), $image_name);
        }

        $category = new SlotsCategory;
        $category->title = $request->title;
        $category->description = $request->description;
        if(!empty(request()->image)){
            $category->image = $image_name;
        }
        $category->save();

        return redirect()->back()->with('success', 'Slot Category Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin']);
        $category = SlotsCategory::find($id);
        return view('slot_category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'title' => 'required',
        ]);
        if(!empty(request()->image)){
            $image_name = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/categories'), $image_name);
        }
        $category = SlotsCategory::find($id);
        $category->title = $request->title;
        $category->description = $request->description;
        if(!empty(request()->image)){
            $category->image = $image_name;
        }
        $category->save();

        return redirect()->back()->with('success', 'Slot category Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = SlotsCategory::find($id);
        $category->delete();
        return redirect()->back()->with('success', 'Slot Category Deleted');
    }
}
