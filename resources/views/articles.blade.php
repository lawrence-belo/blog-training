@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ url('/create_article') }}" class="btn btn-primary">New Post</a>
        @foreach ($articles as $article)
            <div class="row">
                <h3>{{ $article->title }}</h3>
                <h5>{{ $article->author()->first()->first_name }} {{ $article->author()->first()->last_name }}</h5>
                <span class="label label-primary">{{ $article->category()->first()->name }}</span>
                <div class="col-md-12">
                    <img src="{{ $article->image_path }}">
                </div>
                <div class="col-md-12">
                    {!! $article->contents !!}
                </div>
            </div>
        @endforeach
    </div>
@endsection