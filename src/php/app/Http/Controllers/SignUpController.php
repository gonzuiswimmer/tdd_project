<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
{
    public function index(){
        return view('signup');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|max:25',
            'email' => 'required|email:filter|unique:users,email',
            'password' => 'required|min:8',
            ]
        );
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        auth()->login($user);
        return redirect('mypage/posts');
    }
}
