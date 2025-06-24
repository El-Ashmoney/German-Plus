<?php
namespace App\Http\Controllers;
use App\Models\Bug;
use App\Http\Requests;
use Illuminate\Http\Request;

class BugsController extends Controller
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
        $bugs = Bug::all();
        return view('bug.index', compact('bugs'));
    }

}
