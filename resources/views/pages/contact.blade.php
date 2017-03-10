@extends('main')

@section('title', '| Contact Us')

@section('content')
        <div class="row">
            <div class="md-col-12">
                <h4>Contact Us</h4>
                <hr>
                <form action="{{ url('contact') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label name="email">Email:</label>
                        <input id="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label name="subject">Subject:</label>
                        <input id="subject" name="subject" class="form-control">
                    </div>
                    <div class="form-group">
                        <label name="message">Message:</label>
                        <textarea id="message" name="message" class="form-control"></textarea>
                    </div>
                    <input type="submit" value="Send Message" class="btn btn-success">
                </form>
            </divider>
        </div>
@endsection