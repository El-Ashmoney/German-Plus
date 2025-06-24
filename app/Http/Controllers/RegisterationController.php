<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterationController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('registeration.create');
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
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);
        $user = User::create(request(['name', 'email', 'password']));
        $user->roles()->attach(Role::where('name', 'student')->first());

        auth()->login($user);
        return redirect()->to('/');
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }


}
