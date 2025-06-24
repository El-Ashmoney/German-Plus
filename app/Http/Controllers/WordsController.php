<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use Carbon\Carbon;
use App\Evaluations;
use App\Models\Word;
use App\Models\Option;
use App\Models\Category;
use App\Imports\DwImport;

use App\Imports\WordsImport;
use Illuminate\Http\Request;
use App\Imports\Word100Import;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Exceptions\JWTException;

class WordsController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function import()
    {
        Excel::import(new WordsImport, 'words_xls/words.xlsx');
        Excel::import(new DwImport, 'words_xls/dw.xlsx');
        Excel::import(new Word100Import, 'words_xls/100_words.xlsx');

        return redirect('/admin')->with('success', 'Imported successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $words = Word::paginate(50);
        $categories = Category::all();
        return view('word.index', compact('words', 'categories'));
    }

    public function importants()
    {
        $user = auth()->user();
        $words = $user->importants()->paginate(50);

        return view('word.importants', compact('words'));
    }

    public function favourites()
    {
        $user = auth()->user();
        $words = $user->favourites()->paginate(50);

        return view('word.favourites', compact('words'));
    }

    public function valids()
    {
        $words = Word::where('is_valid', 1)->paginate(50);

        return view('word.valids', compact('words'));
    }

    public function quick_search(Request $request)
    {
        $keyword = $request->keyword;

        $words = Word::where('german', 'like', '%' . $keyword . '%')
            ->orWhere('arabic', 'like', '%' . $keyword . '%')
            ->orWhere('english', 'like', '%' . $keyword . '%')
            ->orWhere('note', 'like', '%' . $keyword . '%')
            ->limit(200)
            ->get();

        return response()->json($words, 200);
    }

    public function quick_category_filter(Request $request)
    {
        $category_id = $request->category_id;
        $words = Word::where('category_id', $category_id)->get();
        return response()->json($words, 200);
    }

    public function quick_trash(Request $request)
    {
        $id = $request->id;

        $word = Word::find($id);
        $word->delete();

        $msg = 'Trashed successfully';
        return response()->json(array('msg' => $msg), 200);
    }

    private function generate_ids()
    {
        $max_words = 100;
        $new_percentage = 20;
        $favourite_percentage = 20;
        $important_percentage = 20;
        $top_percentage = 20;
        $low_percentage = 20;

        $new_words = floor($max_words * ($new_percentage / 100));
        $favourite_words = floor($max_words * ($favourite_percentage / 100));
        $important_words = floor($max_words * ($important_percentage / 100));
        $top_words = floor($max_words * ($top_percentage / 100));
        $low_words = floor($max_words * ($low_percentage / 100));
        $all_ids = array();
        $user = Auth::user();


        $new_ids = Word::inRandomOrder()
            ->take($new_words)
            ->pluck('id')
            ->toArray();

        $all_ids = array_merge($all_ids, $new_ids);
        $favourite_ids = Word::rightJoin('favourites', 'favourites.word_id', '=', 'words.id')
            ->whereNotIn('words.id', $all_ids)
            ->where('favourites.user_id', $user->id)
            ->take($favourite_words)
            ->pluck('words.id')
            ->toArray();
        $all_ids = array_merge($all_ids, $favourite_ids);
        $important_ids = Word::rightJoin('importants', 'importants.word_id', '=', 'words.id')
            ->whereNotIn('words.id', $all_ids)
            ->where('importants.user_id', $user->id)
            ->take($important_words)
            ->pluck('words.id')
            ->toArray();
        $all_ids = array_merge($all_ids, $important_ids);

        $top_ids = DB::table('evaluations')
            ->whereNotIn('word_id', $all_ids)
            ->where('user_id', $user->id)
            ->groupBy('word_id')
            ->orderBy(\DB::raw('count(word_id)'), 'DESC')
            ->take($top_words)
            ->pluck('word_id')
            ->toArray();
        $all_ids = array_merge($all_ids, $important_ids);

        $low_ids = DB::table('evaluations')
            ->whereNotIn('word_id', $all_ids)
            ->where('user_id', $user->id)
            ->groupBy('word_id')
            ->orderBy(\DB::raw('count(word_id)'), 'ASC')
            ->take($low_words)
            ->pluck('word_id')
            ->toArray();
        $all_ids = array_merge($all_ids, $important_ids);

        $all_ids_count = count($all_ids);
        if ($all_ids_count < $max_words) {
            $rest_ids = $max_words - $all_ids_count;
            $new_ids = Word::inRandomOrder()
                ->take($rest_ids)
                ->pluck('id')
                ->toArray();
            $all_ids = array_merge($all_ids, $new_ids);
        }
        return $all_ids;
    }

    public function today()
    {
        $user = Auth::user();

        $last_revision = Option::where('name', 'last_revision')->where('user_id', $user->id)->get();
        if ($last_revision->isEmpty()) {
            $all_ids = $this->generate_ids();
            $str_all_ids = implode(",", $all_ids);
            Option::insert(
                ['name' => 'revision_ids', 'value' => $str_all_ids, 'user_id' => $user->id]
            );

            $last_revision = Carbon::now()->toDateTimeString();
            Option::insert(
                ['name' => 'last_revision', 'value' => $last_revision, 'user_id' => $user->id]
            );

        } else {
            $last_revision = Carbon::parse($last_revision->first()->value);
            if (!$last_revision->isToday()) {

                $all_ids = $this->generate_ids();
                $str_all_ids = implode(",", $all_ids);

                Option::updateOrCreate(
                    ['name' => 'revision_ids', 'user_id' => $user->id], ['value' => $str_all_ids]
                );

                $last_revision = Carbon::now()->toDateTimeString();
                Option::updateOrCreate(
                    ['name' => 'last_revision', 'user_id' => $user->id], ['value' => $last_revision]
                );
            } else {
                $last_revision = DB::table('options')->where('name', 'revision_ids')
                    ->where('user_id', $user->id)
                    ->get()
                    ->first()
                    ->value;
                $all_ids = array_map('intval', explode(',', $last_revision));
            }
        }
        $words = Word::whereIn('id', $all_ids)
            ->get();

        return view('word.today', compact('words'));
    }


    public function memorize(Request $request)
    {
        $word_id = $request->id;
        $user = Auth::user();
        $word = Word::find($word_id);
        $word->save();
        $user->evaluations()->attach($word);
        $msg = "Word was evaluated";
        return response()->json(array('msg' => $msg), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = Category::all();
        $request->user()->authorizeRoles(['admin', 'student']);
        return view('word.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'german' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if (!empty(request()->image)) {
            $image_name = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/words'), $image_name);
        }
        if (!empty(request()->word_sound)) {
            $word_sound = time() . '.' . $request->word_sound->getClientOriginalExtension();
            $request->word_sound->move(public_path('sounds'), $word_sound);
        }
        if (!empty(request()->sentence_sound)) {
            $sentence_sound = time() . '.' . $request->sentence_sound->getClientOriginalExtension();
            $request->sentence_sound->move(public_path('sounds'), $sentence_sound);
        }
        $word = new Word;
        $word->german = $request->german;
        $word->arabic = $request->arabic;
        $word->english = $request->english;
        $word->note = $request->note;
        $word->ar_note = $request->ar_note;
        $word->is_valid = isset($request->is_valid);
        $word->category_id = $request->category_id;

        if (!empty(request()->image)) {
            $word->image = $image_name;
        }
        if (!empty(request()->word_sound)) {
            $word->sound = $word_sound;
        }
        if (!empty(request()->sentence_sound)) {
            $word->sentence_sound = $sentence_sound;
        }
        $word->save();

        return redirect()->back()->with('success', 'Word Was Added');
    }

    public function quick_store(Request $request)
    {
        $this->validate(request(), [
            'german' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if (!empty(request()->image)) {
            $image_name = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/words'), $image_name);
        }

        $word = new Word;
        $word->german = $request->german;
        $word->arabic = $request->arabic;
        $word->english = $request->english;
        $word->note = $request->note;
        $word->is_valid = isset($request->is_valid);

        if (!empty(request()->image)) {
            $word->image = $image_name;
        }

        $word->save();
        $msg = "Word was added";
        return response()->json(array('msg' => $msg), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'student']);
        $word = Word::find($id);
        $categories = Category::all();
        return view('word.edit', compact('word', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'german' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if (!empty(request()->image)) {
            $image_name = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/words'), $image_name);
        }
        if (!empty(request()->word_sound)) {
            $word_sound = 'word'.time() . '.' . $request->word_sound->getClientOriginalExtension();
            $request->word_sound->move(public_path('sounds'), $word_sound);
        }
        if (!empty(request()->sentence_sound)) {
            $sentence_sound = 'sentence'.time() . '.' . $request->sentence_sound->getClientOriginalExtension();
            $request->sentence_sound->move(public_path('sounds'), $sentence_sound);
        }
        $word = Word::find($id);
        $word->german = $request->german;
        $word->arabic = $request->arabic;
        $word->english = $request->english;
        $word->note = $request->note;
        $word->ar_note = $request->ar_note;
        $word->category_id = $request->category_id;
        $word->is_valid = isset($request->is_valid);
        if (!empty(request()->image)) {
            $word->image = $image_name;
        }
        if (!empty(request()->word_sound)) {
            $word->sound = $word_sound;
        }
        if (!empty(request()->sentence_sound)) {
            $word->sentence_sound = $sentence_sound;
        }
        $word->save();
        return redirect()->back()->with('success', 'Word Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $word = Word::find($id);
        $word->delete();
        return redirect()->back()->with('success', 'Word Deleted');
    }

    public function favourite(Request $request)
    {
        $is_favourite = $this->get_cpl($request->val);
        $word_id = $request->id;
        $word = Word::find($word_id);
        $user = Auth::user();
        if ($is_favourite == 1) {
            $user->favourites()->attach($word);
            $msg = 'Word added to favourite';
        } else {
            $user->favourites()->detach($word);
            $msg = 'Word remove from favourite';
        }
        return response()->json(array('msg' => $msg), 200);
    }

    public function important(Request $request)
    {
        $is_important = $this->get_cpl($request->val);
        $word_id = $request->id;
        $word = Word::find($word_id);
        $user = Auth::user();
        if ($is_important == 1) {
            $user->importants()->attach($word);
            $msg = 'Word added to important';
        } else {
            $user->importants()->detach($word);
            $msg = 'Word remove from important';
        }
        return response()->json(array('msg' => $msg), 200);
    }

    public function valid(Request $request)
    {
        $word = Word::find($request->id);
        $new_val = $this->get_cpl($request->val);
        $word->is_valid = $new_val;
        $word->save();
        return response()->json(array('msg' => $new_val == 1 ? 'Word added to valid' : 'Word remove from valid'), 200);

    }

    private function get_cpl($val)
    {
        if ($val == 1)
            return 0;
        return 1;
    }
}
