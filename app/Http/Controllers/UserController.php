<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $users = DB::table('users')->when($request->input('name'), function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })->orderBy('id', 'desc')->paginate(10);
        return view('pages.user.index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate =  $request->validate([
            'name' => 'required|max:100|min:4',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric',
            'roles' => 'required|in:ADMIN,STAFF,USER',
            'password' => 'required|min:8',
        ]);


        // $validate['password'] = Hash::make($request->password);
        User::create($validate);
        return redirect()->route('user.index')->with('success', 'User Successfully Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('pages.user.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validate = $request->validate([
            'name' => 'required|max:100|min:4',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'roles' => 'required|in:Admin,Staff,User',
        ]);

        $user->update($validate);
        return redirect()->route('user.index')->with('success', 'User Successfully Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User Successfully Delete');
    }
}
