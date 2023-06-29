<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ["user_id", "label", "latitude", "longitude"];
    protected static $rules = [
        "user_id" => "required",
        "label" => "required",
        "latitude" => "required",
        "longitude" => "required",
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
