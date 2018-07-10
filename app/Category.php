<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exercise;

class Category extends Model
{
    protected $fillable = ['name'];

    public function exercises() {
        return $this->hasMany(Exercise::class);
    }
}
