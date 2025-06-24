<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\UserWord;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserWordController extends Controller
{
    public function create(Request $request, User $users)
    {
        $request->user()->authorizeRoles(['admin', 'student']);
        return view('user_word.create', compact('users'));
    }

    public function store(Request $request)
    {
        $this->validate(request(), [
            'german' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if(!empty(request()->image)){
            $image_name = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/words'), $image_name);
        }
        $word = new UserWord;
        $word->german = $request->german;
        $word->arabic = $request->arabic;
        $word->note = $request->note;
        $word->user_id = $request->user_id;

        if(!empty(request()->image)){
            $word->image = $image_name;
        }
        $word->save();
        return redirect()->back()->with('success', 'Word Was Added');
    }
    public function api_login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

}