@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Create Article</h3>
        <form class="form-horizontal" method="POST" action="{{ url('/create_article') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="control-label col-sm-2">Title</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="blog_title" value="{{ old('blog_title') }}"required/>

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
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Slug</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="slug" value="{{ old('slug') }}" required/>

                    @if ($errors->has('slug'))
                        <span class="help-block">
                            <strong>{{ $errors->first('slug') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <textarea name="blog_contents" id="blog_contents" rows="20" cols="80">{{ old('blog_contents') }}</textarea>

                    @if ($errors->has('blog_contents'))
                        <span class="help-block">
                            <strong>{{ $errors->first('blog_contents') }}</strong>
                        </span>
                    @endif
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
    <script type="text/javascript" src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('blog_contents');
    </script>
@endsection