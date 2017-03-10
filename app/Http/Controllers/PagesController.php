<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Post;
use Session;
use Mail;

// process variable data --> 
// talk to the model -->
// receive from the model -->
// compile or process data from the model if needed -->
// pass that data to the correct view */ -->

class PagesController extends Controller {

	public function getIndex(){
		$posts = Post::orderBy('created_at', 'desc')->limit(4)->get();
		return view('pages.welcome')->withPosts($posts);
	}

	public function getAbout(){
		$first = 'Mustafa';
		$last = 'Bhatti';
		$fullname = $first . " " . $last;
		$email = 'mustapha.pmp@gmail.com';
		$data = [];
		$data['fullname'] = $fullname;
		$data['email'] = $email;
		return view('pages.about')->withData($data);
	}

	public function getContact(){
		$email = 'mustapha.pmp@gmail.com';
		return view('pages.contact')->withEmail($email);
	}

	public function postContact(Request $request) {
		$this->validate($request, [
			'email' => 'required|email',
			'subject' => 'min:3',
			'message' => 'min:10']);

		$data = array(
			'email' => $request->email,
			'subject' => $request->subject,
			'bodyMessage' => $request->message
			);

		Mail::send('emails.contact', $data, function($message) use ($data) {
			$message->from($data['email']);
			$message->to('hummylin4444@gmail.com');
			$message->subject($data['subject']);
		});

		Session::flash('success', 'Your Email was Sent!');

		return redirect()->route('posts.index');
	}

}

