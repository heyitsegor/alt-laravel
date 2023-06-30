<?php

namespace App\Http\Requests;

use App\Models\Location;
use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "label" => ["required", "string", "max:255"],
            "latitude" => ["required", "numeric", "between:-85,85"],
            "longitude" => ["required", "numeric", "between:-180,180"],
        ];
    }
}
