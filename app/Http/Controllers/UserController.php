<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $sort = request()->query("sort", "name");
        $sortOrder = request()->query("sort_order", "asc");

        $users = User::orderBy($sort, $sortOrder)->get();

        return view("users", compact("users", "sort", "sortOrder"));
    }
}
