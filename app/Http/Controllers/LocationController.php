<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = auth()->user()->locations;
        $selectedLocationId = $request->get("location");

        return view("locations", compact("locations", "selectedLocationId"));
    }

    public function create()
    {
        return view("locations.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            "label" => "required",
            "longitude" => "required",
            "latitude" => "required",
        ]);

        $location = new Location([
            "user_id" => auth()->id(),
            "label" => $request->get("label"),
            "longitude" => $request->get("longitude"),
            "latitude" => $request->get("latitude"),
        ]);

        $location->save();

        return redirect("/locations");
    }

    public function show(Location $location)
    {
        return view("locations.show", compact("location"));
    }

    public function edit(Location $location)
    {
        return view("locations.edit", compact("location"));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            "label" => "required",
            "longitude" => "required",
            "latitude" => "required",
        ]);

        $location->fill([
            "label" => $request->get("label"),
            "longitude" => $request->get("longitude"),
            "latitude" => $request->get("latitude"),
        ]);

        $location->save();

        return redirect("/locations");
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect("/locations");
    }
}
