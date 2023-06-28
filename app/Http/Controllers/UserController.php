<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get("sort");
        $order = $request->get("order", "asc");

        if ($sort == "name") {
            $users = User::orderBy("name", $order);
        } elseif ($sort == "created_at") {
            $users = User::orderBy("created_at", $order);
        } else {
            $users = User::query();
        }

        $users = $users->get();

        return view("users.index", ["users" => $users]);
    }
}
