<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // public function admin(Request $request){
    //     if (){}
    // }

    public function login(Request $request){
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user(User::where('email', $request->email));
            Auth::login($user, 1);
            return $user->createToken('user-token', ['create', 'read', 'update', 'delete'], now()->addDay(1));
        }
    }

    public function register(Request $request){
        if (User::where('email', $request->email)->first() == null && User::where('name', $request->name)->first() == null) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->save();
            Auth::login($user, 1);
            return $user->createToken('user-token', ['create', 'read', 'update', 'delete'], now()->addDay(1));
        }
    }

    public function logout(){
        Auth::logout();
    }
}
