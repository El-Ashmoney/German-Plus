<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Models\Train;
use App\Models\Category;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trains = Train::paginate(50);
        $categories = Category::all();
        return view('train.index', compact('trains', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     * @param $word_id
     * @return \Illuminate\Http\Response
     */
    public function create($word_id = 0)
    {
        if($word_id != 0){
            $word = Word::find($word_id);
            return view('train.create', compact('word'));
        }
        return view('train.create');
    }
    public function fetch_word(Request $request)
    {
        $id = $request->id;
        $word = Word::find($id);
        return response()->json($word, 200);
    }
    public function fetch_words(Request $request)
    {
        $ids = $request->ids;
        $ids_arr = array_map( 'intval',explode(',', $ids));
        $words = Word::whereIn('id', $ids_arr)->get();
        return response()->json($words, 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $train = new Train;
        $train->word_id = $request->word_id;
        $train->type = $request->type;
        $train->is_choices_random = isset($request->is_choices_random);
        $train->choices_order = $request->choices_order;
        $train->question = $request->question;
        $train->save();
        return redirect()->back()->with('success', 'Train create successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Train $train
     * @return \Illuminate\Http\Response
     */
    public function show(Train $train)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Train $train
     * @return \Illuminate\Http\Response
     */
    public function edit(Train $train)
    {
        return view('train.edit', compact('train'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Train $train
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Train $train)
    {
        $train->word_id = $request->word_id;
        $train->type = $request->type;
        $train->is_choices_random = isset($request->is_choices_random);
        $train->choices_order = $request->choices_order;
        $train->question = $request->question;
        $train->save();
        return redirect()->back()->with('success', 'Train Was Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Train $train
     * @return \Illuminate\Http\Response
     */
    public function destroy(Train $train)
    {
        $train->delete();
        return redirect()->back()->with('success', 'Train Deleted');

    }

    public function quick_search(Request $request)
    {
        $keyword = $request->keyword;
        $trains = Train::with('Word')->wherehas('Word', function($query) use($keyword){
            $query->where('words.german', 'like', '%' . $keyword . '%')
                ->orWhere('words.arabic', 'like', '%' . $keyword . '%')
                ->orWhere('words.english', 'like', '%' . $keyword . '%')
                ->orWhere('words.note', 'like', '%' . $keyword . '%');
        })->get();
        return response()->json($trains, 200);
    }

    public function quick_trash(Request $request)
    {
        $id = $request->id;

        $train = Train::find($id);
        $train->delete();

        $msg = 'Trashed successfully';
        return response()->json(array('msg' => $msg), 200);
    }

    public function quick_category_filter(Request $request)
    {
        $category_id = $request->category_id;
        $words = Word::whereHas('trains', function ($query) use($category_id){
            $query->where('category_id', $category_id);
        })->get();
        return response()->json($words, 200);
    }

}
