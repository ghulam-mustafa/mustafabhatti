<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    public function category() {
    	return $this -> belongsTo('App\Category');
    }

    public function tags() {
    	return $this -> belongsToMany('App\Tag');
    }

    public function comment() {
    	return $this->hasMany('App\Comment');
    }
}



// it drags everything from the database

// it can also pull from the database like this
// Post::where('title'=> 'My First Title')-get()