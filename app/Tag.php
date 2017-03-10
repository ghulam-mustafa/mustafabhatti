<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function posts() {
    	return $this -> belongsToMany('App\Post');
    	// post p comes before t of tag so post_tag was the table name set. But laravel by default will create that name. so we dont need to mention the name of table specifically.
    }
}
