<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request = $request->all();

        $password = password_hash($request['password'], PASSWORD_ARGON2I);

        $request['password'] = $password;

        return User::create($request);
    }
}
