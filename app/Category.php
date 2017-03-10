<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // manually tell the Category model to use categories table
    protected $table = 'categories';

    public function posts() {
    	return $this -> hasMany('App\Post');
    }
}
