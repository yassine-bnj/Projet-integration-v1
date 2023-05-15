<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function index(){
        return User::all();
    }

    function show($id){
        return User::find($id);
    }
 function store(Request $r){
    $r->validate([
        'name'=>'required',
        'email'=>'required|email',
        'password'=>'required'
    ]); 
    $user=User::create([
        'name'=>$r->name,
        'email'=>$r->email,
        'password'=>Hash::make($r->password),
        'role'=>$r->role
    ]);
 
    return response()->json(['user'=>$user],201);




    }

    function update(Request $r, $id){
        $r->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required'
        ]); 

        $user=User::find($id);
        $user->name=$r->name;
        $user->email=$r->email;
        $user->password=Hash::make($r->password);
        $user->role=$r->role;
        $user->save();
        return response()->json(['user'=>$user],200);


    }

    function destroy($id){
        $user=User::find($id);
        $user->delete();
        return response()->json(['message'=>'User deleted'],200);
    }
    
}
