<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\Tag;
use Session;
use Purifier;
use Image;
use Storage;

class PostController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // create a variable and store all the blog posts in it from the database
        $posts = Post::orderBy('id', 'desc')->paginate(10);
        // return a view and then pass in that variable
        return view('posts.index')->withPosts($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        $tags = Tag::all();
        return view('posts.create')->withCategories($categories)->withTags($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        // validate the data for any malicious code or else for required and provided data
        $this -> validate($request, array(
            'title'         => 'required|max:255',
            'slug'          => 'required|alpha_dash|min:5|max:255|unique:posts,slug',
            'category_id'   => 'required|integer',
            'body'          => 'required',
            'featured_image' => 'sometimes|image'

        ));
        // store in the database
        $post = new Post;

        $post ->title = $request->title;
        $post ->slug = $request->slug;
        $post ->category_id = $request->category_id;
        $post ->body = Purifier::clean($request->body, 'youtube');

        // store the image
        if($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            // we can use encode to save file with only our extenions type required.
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(800, 400)->save($location);

            // save the image in database in posts table image column as below
            $post->image = $filename;
        }

        $post->save();

        $post->tags()->sync($request->tags, false);


        Session::flash('success', 'The blog post was successfully saved!');

        // redirect to another page
        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // R in CRUD. See =>with
        $post = Post::find($id);
        // Session::flash('success','posts->id');
        return view('posts.show')->withPost($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // find the post in database and save it in variable
        $post = Post::find($id);
        $categories = Category::all();
        $cats = array();
        foreach ($categories as $category) {
            $cats[$category->id] = $category->name;

        }

        $tags = Tag::all();
        $tags2 = array();
        foreach ($tags as $tag) {
            $tags2[$tag->id] = $tag->name;
        }

        // return the view and pass in the var we previously created
        return view('posts.edit')->withPost($post)->withCategories($cats)->withTags($tags2);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        // if($request->input('slug') == $post->slug) {
        //     $this -> validate($request, array(
        //     'title' => 'required|max:255',
        //     'category_id' => 'required|integer',
        //     'body' => 'required'

        // )); 
        // }   else {

        // Validated the data
        $this -> validate($request, array(
            'title' => 'required|max:255',
            'slug' => "required|alpha_dash|min:5|max:255|unique:posts,slug,$id",
            'category_id' => 'required|integer',
            'body' => 'required',
            'featured_image' => 'image'

        ));
    
    
        // save data to the database
        $post = Post::find($id);    

        $post->title = $request->input('title');
        $post->slug = $request->input('slug');
        $post->category_id = $request->input('category_id');
        $post->body = Purifier::clean($request->input('body'));

        if($request->hasFile('featured_image')) {
            // add the new photo
            $image = $request->file('featured_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
                // we can use encode to save file with only our extenions type required.
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(800, 400)->save($location);
            $oldFilename = $post->image;
            // update the database to reflect that new image
            $post->image = $filename;
            // delete the old photo
            // config - filesystem configure the storage facade
            Storage::delete($oldFilename);

            
        }

        $post->save();
        if (isset($request->tags)) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->sync(array());
        }


        // set flash data with flash message
        Session::flash('success', 'This post was successfully saved.');

        // redirect with flash data to posts.show
        return redirect()->route('posts.show', $post->id);
    }
    
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->tags()->detach();
        Storage::delete($post->image);
        $post->delete();

        Session::flash('success', 'The post was successfully deleted.');

        return redirect()->route('posts.index');
    }
}
