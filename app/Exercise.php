<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;

class Exercise extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'type', 'category_id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
