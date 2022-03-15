<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {

        $validated = $this->validate($request, [
            'cpf' => "required|string|size:14",
            'email' => 'required|email',
            'name' => 'required|string',
            'password' => 'required|string',
            'birth_date' => 'required|date',
        ]);

        $validated["password"] = Hash::make($validated["password"]);

        $newUser = User::create($validated);
        Auth::login($newUser);

        return response($newUser, 200);
    }

    public function update(User $user, Request $request)
    {

        $validated = $this->validate($request, [
            'name' => 'string',
            'password' => 'string',
            'birth_date' => 'date'
        ]);

        if($validated["password"]){
            $validated["password"] = Hash::make($validated["password"]);
        }

        $user->fill($validated);
        $user->save();

        return response($user, 200);
    }
}
