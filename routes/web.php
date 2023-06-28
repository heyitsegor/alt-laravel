<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", function () {
    return view("welcome");
});

Route::get("/dashboard", function () {
    return view("dashboard");
})
    ->middleware(["auth", "verified"])
    ->name("dashboard");

Route::get("/users", function () {
    $sort = request()->query("sort", "name");
    $sortOrder = request()->query("sort_order", "asc");

    $users = User::orderBy($sort, $sortOrder)->get();

    return view("users", compact("users", "sort", "sortOrder"));
})
    ->middleware(["auth", "verified"])
    ->name("users");

Route::get("/map", function () {
    return view("map");
})
    ->middleware(["auth", "verified"])
    ->name("map");

Route::middleware("auth")->group(function () {
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit"
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update"
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy"
    );
});

require __DIR__ . "/auth.php";
