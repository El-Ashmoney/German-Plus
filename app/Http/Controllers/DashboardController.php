<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function overview(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        $todos = Todo::paginate(20);
        $users_total = User::all()->count();
        $users_today = User::where('created_at', '>=', Carbon::today())->count();

        return view('dashboard.index', compact('todos', 'users_total', 'users_today')); ;
    }


}
