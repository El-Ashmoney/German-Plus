<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate(request(), [
            'title' => 'required',
        ]);
        $todo = new Todo;
        $todo->title = $request->title;
        $todo->save();

        return response()->json($todo, 200);
    }


    public function quick_update(Request $request)
    {
        $this->validate(request(), [
            'title' => 'required',
        ]);

        $todo = Todo::find($request->id);
        $todo->title = $request->title;
        $todo->save();

        $msg = "Todo was updated";
        return response()->json(array('msg'=> $msg), 200);

    }


    public function quick_destroy(Request $request)
    {
        $todo = Todo::find($request->id);
        $todo->delete();
        return response()->json(array('msg'=> true), 200);
    }

    public function done(Request $request)
    {
        $todo = Todo::find($request->id);
        $todo->is_done = $request->is_done;
        $todo->save();
        return response()->json(array('msg'=> true), 200);
    }

}
