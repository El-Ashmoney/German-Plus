<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class SessionsController extends Controller
{
    public function create()
    {
        return view('session.create');

    }
    public function store()
    {
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (auth()->attempt(request(['email', 'password'])) == false) {
            return back()->withErrors([
                'message' => 'The email or password is incorrect, please try again'
            ]);
        }
        return redirect()->to('/');
    }

    public function destroy()
    {
        auth()->logout();

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
