<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;

class ArticleController extends Controller
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
        $article = Article::all();
        return view('article.index', compact('article'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        $categories = Category::all();
        return view('article.create', compact('categories'));
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
            'content' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slug' => 'required'
        ]);
        if(!empty(request()->image)){
            $image_name = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images'), $image_name);
        }
        $article = new Article;
        $article->title = $request->title;
        $article->content = $request->content;
        if(!empty(request()->image)){
            $article->image = $image_name;
        }
        $article->slug = $request->slug;
        $article->category_id = $request->category_id;
        $article->save();

        return redirect()->back()->with('success', 'Article Created');
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
        $article = Article::find($id);
        $categories = Category::all();

        return view('article.edit', compact('article', 'categories'));
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
            'content' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slug' => 'required'
        ]);
        if(!empty(request()->image)){
            $image_name = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images'), $image_name);
        }
        $article = Article::find($id);
        $article->title = $request->title;
        $article->content = $request->content;
        if(!empty(request()->image)){
            $article->image = $image_name;
        }
        $article->category_id = $request->category_id;
        $article->slug = $request->slug;
        $article->save();

        return redirect()->back()->with('success', 'Article Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        $article->delete();
        return redirect()->back()->with('success', 'Article Deleted');
    }
}