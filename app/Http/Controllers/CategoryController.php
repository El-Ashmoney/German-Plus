<?php

namespace App\Http\Controllers;

use File;
use Response;
use App\Models\Word;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
        $categories = Category::orderBy('index')->get();
        return view('category.index', compact('categories'));
    }
    public function words($id){
        $words = Word::where('category_id', $id)->orderBy('index')->get();
        return view('category.words', compact('words'));
    }

    public function save_words_order(Request $request){
        $oders = $request->orders;
        foreach ($oders as $order){
            $orderObj = Word::find($order['id']);
            $orderObj->index = $order['index'];
            $orderObj->save();
        }
        return response()->json('success', 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        return view('category.create');
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

        $category = new Category;
        $category->title = $request->title;
        $category->description = $request->description;
        $category->index = $request->index;
        if(!empty(request()->image)){
            $category->image = $image_name;
        }
        $category->save();

        return redirect()->back()->with('success', 'Category Created');
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
        $category = Category::find($id);
        return view('category.edit', compact('category'));
    }

    /**
     * Export words of category as HTML article.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin']);
        $words = Word::where('category_id', $id)->orderBy('index')->get();
        $category = Category::find($id);
        $title = empty($category->description) ? $category->title : $category->description;
        $html = '<h2 dir="rtl" style="text-align: right;">'.$title.'</h2>';
        foreach($words as $word){
            if($word->ar_note && $word->note){
                $html .= '<p dir="rtl" style="text-align: right;">'.$word->ar_note.'</p>';
                $html .= '<p dir="ltr" style="text-align: left;">'.$word->note.'</p>';
            }
            if($word->sentence_sound){
                $html .= '[embed]https://app.germanschool.info/sounds/'.$word->sentence_sound.'[/embed]';
            }
        }
        $file= public_path(). "/export.html";
        File::put($file, $html);
        return Response::download($file, 'filename.html');
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
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if(!empty(request()->image)){
            $image_name = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/categories'), $image_name);
        }
        $category = Category::find($id);
        $category->title = $request->title;
        $category->description = $request->description;
        $category->index = $request->index;
        if(!empty(request()->image)){
            $category->image = $image_name;
        }
        $category->save();

        return redirect()->back()->with('success', 'category Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->back()->with('success', 'category Deleted');
    }
}
