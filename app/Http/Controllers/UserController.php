<?php

namespace App\Http\Controllers;

use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->orderBy('created_at', 'desc')->get();
        return view('users.index', ['users' => $users]);
    }
}