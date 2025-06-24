<?php

namespace App\Http\Controllers;

use App\Models\Grammar;
use Illuminate\Http\Request;

class GrammarController extends Controller
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
        $grammars = Grammar::all();
        return view('grammar.index', compact('grammars'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        return view('grammar.create');
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
        ]);

        $grammar = new Grammar;
        $grammar->title = $request->title;
        $grammar->content = $request->content;
        $grammar->save();

        return redirect()->back()->with('success', 'Grammar Created');
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
        $grammar = Grammar::find($id);
        return view('grammar.edit', compact('grammar'));
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
        ]);

        $grammar = Grammar::find($id);
        $grammar->title = $request->title;
        $grammar->content = $request->content;
        $grammar->save();

        return redirect()->back()->with('success', 'Grammar Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $grammar = Grammar::find($id);
        $grammar->delete();
        return redirect()->back()->with('success', 'Grammar Deleted');
    }
}
