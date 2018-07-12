<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exercise;
use App\User;

class Set extends Model
{
    protected $guarded = [];

    public function exercise() {
        return $this->belongsTo(Exercise::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
