@extends('common.app')

@section('content')
    <div class="container">
        <div class="row">
            @if (session('status'))
                <div class="col-md-12 alert alert-success">{!! session('status') !!}</div>
            @endif
        </div>
        <div class="row">
            <a href="{{ url('/create_article') }}" id="new_post" class="btn btn-primary">New Post</a>
        </div>
        @foreach ($articles as $article)
            <div class="row">
                <div class="col-md-3">
                    <h3>{{ $article->title }}</h3>
                </div>
                <div class="col-md-3 col-md-offset-6">
                    <a href="{{ url('/update_article/' . $article->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <a href="{{ url('/delete_article/' . $article->id) }}" class="btn btn-sm btn-danger">Delete</a>
                </div>
                <div class="col-md-12">
                    <h5>{{ $article->author()->first()->first_name }} {{ $article->author()->first()->last_name }}</h5>
                </div>
                <div class="col-md-12">
                    <span class="label label-primary">{{ $article->category()->first()->name }}</span>
                </div>
                <div class="col-md-12">
                    <br>
                    <img src="{{ $article->image_path }}" width="300px">
                </div>
                <div class="col-md-12">
                    {!! $article->contents !!}
                </div>
            </div>
            <hr>
        @endforeach
    </div>
@endsection