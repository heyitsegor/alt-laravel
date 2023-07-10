<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Http\Requests\LocationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = auth()->user()->locations;
        $selectedLocationId = $request->get("location");

        return view(
            "locations.index",
            compact("locations", "selectedLocationId")
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "label" => [
                "required",
                "string",
                "max:255",
                Rule::unique("locations")->where(function ($query) use (
                    $request
                ) {
                    return $query->where("user_id", auth()->id());
                }),
            ],
            "latitude" => [
                "required",
                "numeric",
                "min:-85",
                "max:85",
                Rule::unique("locations")->where(function ($query) use (
                    $request
                ) {
                    return $query
                        ->where("user_id", auth()->id())
                        ->where("longitude", $request->get("longitude"));
                }),
            ],
            "longitude" => [
                "required",
                "numeric",
                "min:-180",
                "max:180",
                Rule::unique("locations")->where(function ($query) use (
                    $request
                ) {
                    return $query
                        ->where("user_id", auth()->id())
                        ->where("latitude", $request->get("latitude"));
                }),
            ],
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

    public function update(
        LocationRequest $request,
        Location $location
    ): RedirectResponse {
        if (!Gate::allows("update-location", $location)) {
            abort(403);
        }
        $location->fill($request->validated());

        $location->save();

        return redirect()
            ->route("locations.index")
            ->with("success", "Location saved!");
    }

    public function destroy(Location $location)
    {
        if (!Gate::allows("delete-location", $location)) {
            abort(403);
        }
        $location->delete();

        return redirect("/locations");
    }
}
