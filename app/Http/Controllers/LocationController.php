<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

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

    public function store(Request $request): RedirectResponse
    {
        /**FIXME:
        Latitude: -85 to +85
        Longitude: -180 to +180**/
        $request->validate([
            "label" => ["required", "string", "max:255"],
            "latitude" => ["required", "numeric", "min:-85", "max:85"],
            "longitude" => ["required", "numeric", "min:-180", "max:180"],
        ]);

        $location = new Location([
            "user_id" => auth()->id(),
            "label" => $request->get("label"),
            "latitude" => $request->get("latitude"),
            "longitude" => $request->get("longitude"),
        ]);

        $location->save();

        return Redirect::route("locations.index")->with(
            "status",
            "location-saved"
        );
    }

    public function edit(Location $location)
    {
        return view("locations", compact("location"));
    }

    public function update(
        Request $request,
        Location $location
    ): RedirectResponse {
        $request->validate([
            "label" => "required",
            "latitude" => "required",
            "longitude" => "required",
        ]);

        $location->fill([
            "label" => $request->get("label"),
            "latitude" => $request->get("latitude"),
            "longitude" => $request->get("longitude"),
        ]);

        $location->save();

        return Redirect::route("locations.index")->with(
            "status",
            "location-saved"
        );
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect("/locations");
    }
}
