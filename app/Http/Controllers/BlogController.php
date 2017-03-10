<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Post;


class BlogController extends Controller
{
    
	public function getIndex() {

		$posts = Post::paginate(10);
		return view('blog.index')->withPosts($posts);
	}

    public function getSingle($id) {

		// fetch from the DB based on slug
		$post = Post::where('slug', '=', $id)->first();

		// return the view and pass in the post object

	   	// return $slug;
    	// not {{ }} will be used because we are not in view. 
    	return view('blog.single')->withPost($post);
    }
}
