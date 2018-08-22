@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Create Article</h3>
        <form class="form-horizontal" method="POST" action="{{ url('/update_article/' . $article->id) }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="control-label col-sm-2">Title</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="blog_title" value="{{ old('blog_title', $article->title) }}"required/>

                    @if ($errors->has('blog_title'))
                        <span class="help-block">
                            <strong>{{ $errors->first('blog_title') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Category</label>
                <div class="col-sm-10">
                    <select name="category">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category', $article->article_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Slug</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="slug" value="{{ old('slug', $article->slug) }}" required/>

                    @if ($errors->has('slug'))
                        <span class="help-block">
                            <strong>{{ $errors->first('slug') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <textarea name="blog_contents" id="blog_contents" rows="20" cols="80">{{ old('blog_contents', $article->contents) }}</textarea>

                    @if ($errors->has('blog_contents'))
                        <span class="help-block">
                            <strong>{{ $errors->first('blog_contents') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-2">
                    <input id="image_path" name="image_path" type="text" class="form-control" value="{{ old('image_path', $article->image_path) }}">
                </div>
                <div class="col-sm-2">
                    <a href="#" id="ckfinder-popup" class="btn btn-success">Select Image</a>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('custom_js')
    @include('ckfinder::setup')
    <script type="text/javascript" src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        var editor = CKEDITOR.replace('blog_contents');
        CKFinder.setupCKEditor(editor);

        var button1 = document.getElementById( 'ckfinder-popup' );

        button1.onclick = function() {
            selectFileWithCKFinder( 'image_path' );
        };

        function selectFileWithCKFinder( elementId ) {
            CKFinder.popup( {
                chooseFiles: true,
                width: 800,
                height: 600,
                onInit: function( finder ) {
                    finder.on( 'files:choose', function( evt ) {
                        var file = evt.data.files.first();
                        var output = document.getElementById( elementId );
                        output.value = file.getUrl();
                    } );

                    finder.on( 'file:choose:resizedImage', function( evt ) {
                        var output = document.getElementById( elementId );
                        output.value = evt.data.resizedUrl;
                    } );
                }
            } );
        }
    </script>
@endsection
