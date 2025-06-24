<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
    public function index(User $users)
    {
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Role $roles)
    {
        $request->user()->authorizeRoles(['admin']);
        return view('user.create', compact('roles'));
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
            'email' => 'unique:users,email',
            'password' => 'required|confirmed'
        ]);
        $user = User::create(request(['name', 'email', 'password']));
        $user->roles()->sync([$request->role_id]);
        $user->save();

        $options = Option::insert([
            ['name' => 'max_words','value' => 100, 'user_id' => $user->id ],
            ['name' => 'new_percentage', 'value' => 20, 'user_id' => $user->id ],
            ['name' => 'favourite_percentage', 'value' => 20,'user_id' => $user->id ],
            ['name' => 'important_percentage', 'value' => 20, 'user_id' => $user->id ],
            ['name' => 'top_percentage', 'value' => 20, 'user_id' => $user->id ],
            ['name' => 'low_percentage', 'value' => 20, 'user_id' => $user->id ]
        ]);

        return redirect()->back()->with('success', 'User Created');
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
        $user_id = $request->user;
        $user = User::find($user_id);
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
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
            'name' => 'required',
            'email' => 'unique:users,email,'.$id,
            'password' => 'confirmed'
        ]);
        if(empty($request->password) && empty($request->password_confirmation)){
            $user = User::where('id', $id)->update( ['name'=>$request->name, 'email'=>$request->email] );
        }else{
            $user = User::where('id', $id)->update( ['name'=>$request->name, 'email'=>$request->email, 'password'=>Hash::make($request->password) ] );
        }
        $user = User::find($id);
        $user->roles()->sync([$request->role_id]);
        $user->save();

        return redirect()->back()->with('success', 'User Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->back()->with('success', 'User Deleted');
    }
}
